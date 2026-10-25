<?php

namespace Hschottm\SurveyBundle\Condition;

use Contao\DataContainer;
use Contao\System;

/** Contao creates all descendants before invoking the parent's oncopy callback. */
final class CopyConditions
{
    private static array $pages = [];
    private static array $questions = [];

    private static function db()
    {
        return System::getContainer()->get('database_connection');
    }

    public function question($newId, DataContainer $dc): void
    {
        $page = (int) self::db()->fetchOne('SELECT pid FROM tl_survey_question WHERE id=?', [$newId]);
        self::$questions[$page][(int) $dc->id] = (int) $newId;
    }

    public function page($newId, DataContainer $dc): void
    {
        $db = self::db();
        $sourceSurvey = (int) $db->fetchOne('SELECT pid FROM tl_survey_page WHERE id=?', [$dc->id]);
        $targetSurvey = (int) $db->fetchOne('SELECT pid FROM tl_survey_page WHERE id=?', [$newId]);
        if ($sourceSurvey !== $targetSurvey) {
            // A whole survey is being copied. Other destination pages may not exist yet.
            self::$pages[$targetSurvey][(int) $dc->id] = (int) $newId;
            return;
        }
        $this->copy([(int) $dc->id => (int) $newId], self::$questions[(int) $newId] ?? [], false);
        unset(self::$questions[(int) $newId]);
    }

    public function survey($newId, DataContainer $dc): void
    {
        $pages = self::$pages[(int) $newId] ?? [];
        $questions = [];
        foreach ($pages as $newPage) {
            $questions += self::$questions[$newPage] ?? [];
        }
        $this->copy($pages, $questions, true);
        foreach ($pages as $newPage) {
            unset(self::$questions[$newPage]);
        }
        unset(self::$pages[(int) $newId]);
    }

    private function copy(array $pages, array $questions, bool $wholeSurvey): void
    {
        self::db()->transactional(static function ($db) use ($pages, $questions, $wholeSurvey) {
            foreach ($pages as $oldPage => $newPage) {
                foreach (Editor::rows($oldPage) as $condition) {
                    $question = (int) $condition['qid'];
                    $target = (int) $condition['pageid'];
                    if ($wholeSurvey && (($question !== 0 && !isset($questions[$question])) || !isset($pages[$target]))) {
                        throw new \RuntimeException('Cannot copy survey conditions: a referenced question or page is outside the copied survey.');
                    }
                    unset($condition['id']);
                    $condition['pid'] = $newPage;
                    $condition['qid'] = $questions[$question] ?? $question;
                    $condition['pageid'] = $pages[$target] ?? $target;
                    $condition['tstamp'] = time();
                    $condition[$db->quoteIdentifier('condition')] = $condition['condition'];
                    if ($db->quoteIdentifier('condition') !== 'condition') {
                        unset($condition['condition']);
                    }
                    $db->insert('tl_survey_condition', $condition);
                }
            }
        });
    }
}
