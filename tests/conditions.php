<?php

use Hschottm\SurveyBundle\Condition\Editor;
use Hschottm\SurveyBundle\Condition\Rules;

$choice = ['questiontype' => 'multiplechoice'];
$number = ['questiontype' => 'openended', 'openended_subtype' => 'oe_integer'];
$textQuestion = ['questiontype' => 'openended', 'openended_subtype' => 'oe_singleline'];
check(Rules::matches([1 => '1', 3 => '3'], '=', '3', $choice), 'Selected multiple choice not recognized');
check(!Rules::matches([1 => '1'], '!=', '1', $choice), 'Selected option incorrectly excluded');
check(Rules::matches([1 => '1'], '!=', '2', $choice), 'Unselected option not recognized');
foreach ([null, '', [], '0', ['']] as $empty) {
    check(!Rules::matches($empty, '!=', '2', $choice), 'Missing choice must not match inequality');
}
check(Rules::matches('0', '=', '0', $number), 'Numeric zero is a valid answer');
check(Rules::matches('12', '>', '2', $number), 'Numeric comparison is lexicographic');
check(!Rules::matches('invalid', '>', '2', $number), 'Invalid numeric value accepted');
check(Rules::matches('001', '=', '001', $textQuestion), 'Text comparison lost leading zeros');
check(!Rules::matches('001', '=', '1', $textQuestion), 'Text comparison coerced numbers');
$rows = [
    ['id' => 4, 'grp' => 20, 'qid' => 0, 'relation' => '=', 'condition' => '', 'pageid' => 4],
    ['id' => 2, 'grp' => 10, 'qid' => 1, 'relation' => '=', 'condition' => '1', 'pageid' => 3],
    ['id' => 3, 'grp' => 10, 'qid' => 2, 'relation' => '>=', 'condition' => '18', 'pageid' => 3],
];
$questions = [1 => $choice, 2 => $number];
$answers = [1 => ['value' => '1'], 2 => '21'];
check(Rules::evaluate($rows, fn ($id) => $answers[$id] ?? null, fn ($id) => $questions[$id] ?? null) === 3, 'Ordered AND rule failed');
$answers[2] = null;
check(Rules::evaluate($rows, fn ($id) => $answers[$id] ?? null, fn ($id) => $questions[$id] ?? null) === 4, 'Missing answer must fall through to fallback');
check(Rules::evaluate(array_slice($rows, 1), fn ($id) => null, fn ($id) => $questions[$id] ?? null) === null, 'No matching rule must use normal page order');
echo "PASS: condition evaluation, AND groups, priority, fallback, text/numeric values and missing answers.\n";

// Isolate persistence from the live survey data.
$db = new class {
    public array $conditions = [];
    public array $writes = [];
    public function fetchAssociative($sql, $params) { return ['id' => 1, 'pid' => 10, 'sorting' => 128]; }
    public function fetchAllAssociative($sql, $params) {
        if (str_contains($sql, 'tl_survey_condition')) return $this->conditions;
        if (str_contains($sql, 'tl_survey_page')) return [
            ['id' => 1, 'title' => 'Start', 'sorting' => 128],
            ['id' => 2, 'title' => 'Details', 'sorting' => 256],
        ];
        if ($params[0] !== 1) return [];
        $base = ['questiontype' => 'openended', 'openended_subtype' => 'oe_integer', 'multiplechoice_subtype' => '', 'choices' => '', 'addother' => '', 'othertitle' => ''];
        return [
            array_replace($base, ['id' => 11, 'title' => 'Age']),
            array_replace($base, ['id' => 12, 'title' => 'Role <script>alert(1)</script>', 'questiontype' => 'multiplechoice', 'multiplechoice_subtype' => 'mc_multipleresponse', 'choices' => serialize(['A', 'B'])]),
            array_replace($base, ['id' => 13, 'title' => 'Matrix', 'questiontype' => 'matrix']),
        ];
    }
    public function quoteIdentifier($name) { return $name; }
    public function transactional($callback) { return $callback($this); }
    public function delete($table, $where) { $this->writes[] = ['delete', $where]; $this->conditions = []; }
    public function insert($table, $row) { $this->writes[] = ['insert', $row]; $this->conditions[] = $row + ['id' => count($this->conditions) + 1]; }
};
$container->set('database_connection', $db);
(new ReflectionProperty(Contao\System::class, 'arrLanguageFiles'))->setValue(null, ['tl_survey_page' => ['en' => 'en'], 'tl_survey_question' => ['en' => 'en']]);
require $root.'/src/Resources/contao/languages/en/tl_survey_page.php';
$valid = [['target' => '2', 'conditions' => [['question' => '11', 'operator' => '>=', 'value' => '18'], ['question' => '12', 'operator' => '!=', 'value' => '2']]]];
check(Editor::validate(json_encode($valid), 1) === $valid, 'Valid rule rejected');
foreach ([
    [['target' => '99', 'conditions' => $valid[0]['conditions']]],
    [['target' => '1', 'conditions' => $valid[0]['conditions']]],
    [['target' => '2', 'conditions' => [['question' => '13', 'operator' => '=', 'value' => '1']]]],
    [['target' => '2', 'conditions' => [['question' => '12', 'operator' => '>', 'value' => '1']]]],
    [['target' => '2', 'conditions' => [['question' => '12', 'operator' => '=', 'value' => '9']]]],
    [['target' => '2', 'conditions' => [['question' => [], 'operator' => '=', 'value' => '1']]]],
    [['target' => '2', 'conditions' => []]],
] as $invalid) {
    try {
        Editor::validate(json_encode($invalid), 1);
        throw new RuntimeException('Invalid condition was accepted');
    } catch (InvalidArgumentException) {}
}
$dc = (new ReflectionClass(Contao\DC_Table::class))->newInstanceWithoutConstructor();
$dc->id = 1;
$editor = new Editor();
$editor->stage(json_encode($valid), $dc);
check($db->writes === [], 'Staging must not write before the page is successfully saved');
$editor->save($dc);
check(count($db->conditions) === 2 && $db->conditions[0]['pid'] === 1, 'Conditions not persisted for the current page');
check(json_decode($editor->load('', $dc), true) === $valid, 'Rules do not survive reload');
$writeCount = count($db->writes);
$editor->stage(json_encode($valid), $dc);
$editor->save($dc);
check(count($db->writes) === $writeCount, 'Unchanged rules must retain their record IDs');
$db->conditions = [['id' => 50, 'pid' => 1, 'grp' => 7, 'qid' => 13, 'relation' => '=', 'condition' => 'old', 'pageid' => 2]];
$legacy = $editor->load('', $dc);
check(Editor::validate($legacy, 1) === json_decode($legacy, true), 'Existing unsupported rule must be preserved');
$editor->stage($legacy, $dc);
$editor->save($dc);
check($db->conditions[0]['id'] === 50, 'Legacy rule changed during an unrelated page save');
$editor->stage('[]', $dc);
$editor->save($dc);
check($db->conditions === [], 'Removing all rules must restore normal navigation');
$config = Editor::configuration(1);
$html = $twig->render('@Contao/be_condition_wizard.html.twig', [
    'configuration' => json_encode($config), 'value' => json_encode($valid), 'name' => 'conditions', 'id' => 'conditions', 'labels' => $config['labels'],
]);
check(!str_contains($html, '<script>'), 'Condition data is not escaped');
if (getenv('SURVEY_EDITOR_PREVIEW')) {
    file_put_contents(getenv('SURVEY_EDITOR_PREVIEW'), '<!doctype html><meta charset="utf-8"><title>Condition editor test</title><link rel="stylesheet" href="/condition-editor.css"><style>body{font:15px system-ui;padding:24px;max-width:1100px}select,input,button{font:inherit;padding:7px}button{cursor:pointer}label{display:block}.tl_help{color:#555}</style><h1>Navigation after this page</h1><form>'.$html.'</form><script src="/condition-editor.js"></script>');
}
echo "PASS: condition validation, deferred saves, reload, deletion, preservation of legacy rules and escaped markup.\n";

$widget = new Hschottm\SurveyBundle\ConditionWizard([
    'id' => 'conditions', 'name' => 'conditions', 'value' => json_encode($valid), 'dataContainer' => $dc,
]);
check(str_contains($widget->generate(), 'survey-condition-editor'), 'Real Contao widget failed to render');
$widget->setInputCallback(fn () => json_encode($valid));
$widget->validate();
check(!$widget->hasErrors(), 'Real Contao widget rejects valid rules');
$widget->setInputCallback(fn () => '{invalid');
$widget->validate();
check($widget->hasErrors(), 'Real Contao widget must block invalid submissions');
echo "PASS: real Contao condition widget renders and validates submissions.\n";
