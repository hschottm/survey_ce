<?php
require 'vendor/autoload.php';
$kernel = Contao\ManagerBundle\HttpKernel\ContaoKernel::fromInput(getcwd(), new Symfony\Component\Console\Input\ArrayInput(['--env' => 'prod']));
$kernel->boot();
$c = $kernel->getContainer();
$c->get('contao.framework')->initialize();
$db = $c->get('database_connection');
Contao\Controller::loadDataContainer('tl_survey');
$testId = (int) getenv('SURVEY_COPY_TEST_ID');
if (!$testId) throw new RuntimeException('Set SURVEY_COPY_TEST_ID to a survey ID in the test installation.');
$source = $db->fetchAssociative('SELECT * FROM tl_survey WHERE id=?', [$testId]);
if (!$source) throw new RuntimeException('Test survey not found');
$oldId = (int) $source['id'];
unset($source['id']);
$source['title'] = 'Temporary copy regression test';
$dc = (new ReflectionClass(Contao\DC_Table::class))->newInstanceWithoutConstructor();
$dc->id = $oldId;
$db->beginTransaction();
try {
    $db->insert('tl_survey', $source);
    $newId = (int) $db->lastInsertId();
    (new ReflectionMethod(Contao\DC_Table::class, 'copyChildren'))->invoke($dc, 'tl_survey', $newId, $oldId, $newId);
    (new Hschottm\SurveyBundle\Condition\CopyConditions())->survey($newId, $dc);
    foreach (['tl_survey_page', 'tl_survey_participant', 'tl_survey_result', 'tl_survey_pin_tan'] as $table) {
        $n = (int) $db->fetchOne('SELECT COUNT(*) FROM '.$table.' WHERE pid=?', [$newId]);
        if ($table === 'tl_survey_page') {
            if ($n !== (int) $db->fetchOne('SELECT COUNT(*) FROM tl_survey_page WHERE pid=?', [$oldId])) throw new RuntimeException('Pages missing');
        } elseif ($n !== 0) throw new RuntimeException('Participant data copied');
    }
    $countQuestions = static fn ($id) => (int) $db->fetchOne('SELECT COUNT(*) FROM tl_survey_question q JOIN tl_survey_page p ON p.id=q.pid WHERE p.pid=?', [$id]);
    if ($countQuestions($oldId) !== $countQuestions($newId)) throw new RuntimeException('Questions missing');
    $oldCount = (int) $db->fetchOne('SELECT COUNT(*) FROM tl_survey_condition c JOIN tl_survey_page p ON p.id=c.pid WHERE p.pid=?', [$oldId]);
    $copied = $db->fetchAllAssociative('SELECT c.* FROM tl_survey_condition c JOIN tl_survey_page p ON p.id=c.pid WHERE p.pid=?', [$newId]);
    if (count($copied) !== $oldCount) throw new RuntimeException('Conditions missing');
    foreach ($copied as $condition) {
        if ((int) $db->fetchOne('SELECT pid FROM tl_survey_page WHERE id=?', [$condition['pageid']]) !== $newId) throw new RuntimeException('Old destination referenced');
        if ($condition['qid'] && (int) $db->fetchOne('SELECT p.pid FROM tl_survey_question q JOIN tl_survey_page p ON p.id=q.pid WHERE q.id=?', [$condition['qid']]) !== $newId) throw new RuntimeException('Old question referenced');
    }
    echo 'PASS: real Contao child-copy operation copied pages/questions and '.count($copied).' conditions with new references; no participant data copied.'.PHP_EOL;
    // Standalone page copies keep references to other pages in the same survey.
    $page = $db->fetchAssociative('SELECT p.* FROM tl_survey_page p WHERE p.pid=? AND EXISTS (SELECT 1 FROM tl_survey_condition c WHERE c.pid=p.id) ORDER BY p.id LIMIT 1', [$oldId]);
    if ($page) {
        $oldPage = (int) $page['id'];
        unset($page['id']);
        $page['conditions'] = '';
        $db->insert('tl_survey_page', $page);
        $newPage = (int) $db->lastInsertId();
        $dc->id = $oldPage;
        (new ReflectionMethod(Contao\DC_Table::class, 'copyChildren'))->invoke($dc, 'tl_survey_page', $newPage, $oldPage, $newPage);
        (new Hschottm\SurveyBundle\Condition\CopyConditions())->page($newPage, $dc);
        $originalRules = Hschottm\SurveyBundle\Condition\Editor::rows($oldPage);
        $newRules = Hschottm\SurveyBundle\Condition\Editor::rows($newPage);
        if (count($originalRules) !== count($newRules)) throw new RuntimeException('Page conditions missing');
        foreach ($originalRules as $i => $rule) {
            $expectedTarget = (int) $rule['pageid'] === $oldPage ? $newPage : (int) $rule['pageid'];
            if ((int) $newRules[$i]['pageid'] !== $expectedTarget) throw new RuntimeException('Page target mapping failed');
            $questionPage = (int) $db->fetchOne('SELECT pid FROM tl_survey_question WHERE id=?', [$rule['qid']]);
            if ($questionPage === $oldPage) {
                if ((int) $db->fetchOne('SELECT pid FROM tl_survey_question WHERE id=?', [$newRules[$i]['qid']]) !== $newPage) throw new RuntimeException('Local question mapping failed');
            } elseif ((int) $newRules[$i]['qid'] !== (int) $rule['qid']) throw new RuntimeException('External question changed');
        }
        echo 'PASS: standalone page copy preserves external references and remaps local references.'.PHP_EOL;
    }
} finally {
    $db->rollBack();
    echo 'All test copies rolled back.'.PHP_EOL;
}
