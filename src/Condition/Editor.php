<?php

namespace Hschottm\SurveyBundle\Condition;

use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;

/** The page field is virtual; condition records remain the source of truth. */
final class Editor
{
    private static array $pending = [];

    private static function db()
    {
        return System::getContainer()->get('database_connection');
    }

    public static function rows(int $page): array
    {
        return self::db()->fetchAllAssociative('SELECT * FROM tl_survey_condition WHERE pid=? ORDER BY grp, id', [$page]);
    }

    public static function groups(array $rows): array
    {
        $groups = [];
        foreach ($rows as $row) {
            $key = $row['grp'];
            $groups[$key] ??= ['target' => (string) $row['pageid'], 'conditions' => []];
            $groups[$key]['conditions'][] = [
                'question' => (string) $row['qid'],
                'operator' => (string) $row['relation'],
                'value' => (string) $row['condition'],
            ];
        }
        return array_values($groups);
    }

    public function load($value, DataContainer $dc): string
    {
        return json_encode(self::groups(self::rows((int) $dc->id)), JSON_THROW_ON_ERROR);
    }

    public static function configuration(int $page): array
    {
        System::loadLanguageFile('tl_survey_page');
        System::loadLanguageFile('tl_survey_question');
        $db = self::db();
        $current = $db->fetchAssociative('SELECT id, pid, sorting FROM tl_survey_page WHERE id=?', [$page]);
        $pages = $current ? $db->fetchAllAssociative('SELECT id, title, sorting FROM tl_survey_page WHERE pid=? ORDER BY sorting, id', [$current['pid']]) : [];
        $questions = [];
        foreach ($pages as $index => $p) {
            foreach ($db->fetchAllAssociative('SELECT * FROM tl_survey_question WHERE pid=? ORDER BY sorting, id', [$p['id']]) as $q) {
                $type = $q['questiontype'];
                $numeric = $type === 'openended' && in_array($q['openended_subtype'], ['oe_integer', 'oe_float'], true);
                $supported = $type === 'multiplechoice' || ($type === 'openended' && !in_array($q['openended_subtype'], ['oe_date', 'oe_time'], true));
                $choices = [];
                if ($type === 'multiplechoice') {
                    $labels = $q['multiplechoice_subtype'] === 'mc_dichotomous'
                        ? [$GLOBALS['TL_LANG']['tl_survey_question']['yes'], $GLOBALS['TL_LANG']['tl_survey_question']['no']]
                        : StringUtil::deserialize($q['choices'], true);
                    if ($q['addother'] && $q['multiplechoice_subtype'] !== 'mc_dichotomous') {
                        $labels[] = $q['othertitle'];
                    }
                    foreach (array_values($labels) as $i => $label) {
                        $choices[] = ['value' => (string) ($i + 1), 'label' => ($i + 1).' – '.strip_tags(StringUtil::decodeEntities((string) $label))];
                    }
                }
                $questions[] = [
                    'id' => (string) $q['id'],
                    'label' => ($index + 1).' · '.strip_tags(StringUtil::decodeEntities($q['title'])).' (#'.$q['id'].')',
                    'supported' => $supported && (int) $p['sorting'] <= (int) $current['sorting'],
                    'numeric' => $numeric,
                    'multiple' => $type === 'multiplechoice' && $q['multiplechoice_subtype'] === 'mc_multipleresponse',
                    'operators' => $numeric ? ['=', '!=', '>', '<', '>=', '<='] : ['=', '!='],
                    'choices' => $choices,
                ];
            }
        }

        return [
            'currentPage' => (string) $page,
            'questions' => $questions,
            'pages' => array_map(static fn ($p) => ['value' => (string) $p['id'], 'label' => strip_tags(StringUtil::decodeEntities($p['title'])).' (#'.$p['id'].')'], $pages),
            'labels' => $GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor'],
        ];
    }

    public static function validate(string $json, int $page): array
    {
        $config = self::configuration($page);
        $error = $config['labels']['invalid'];
        try {
            $groups = json_decode($json, true, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new \InvalidArgumentException($error);
        }
        if (!is_array($groups) || !array_is_list($groups) || count($groups) > 100) {
            throw new \InvalidArgumentException($error);
        }
        $questions = array_column($config['questions'], null, 'id');
        $targets = array_column($config['pages'], 'value');
        $existing = self::groups(self::rows($page));
        foreach ($groups as $group) {
            // Preserve legacy rules verbatim, including unsupported question types.
            if (in_array($group, $existing, true)) {
                continue;
            }
            if (!is_array($group) || !isset($group['target'], $group['conditions'])
                || !is_string($group['target']) || !in_array($group['target'], $targets, true) || (int) $group['target'] === $page
                || !is_array($group['conditions']) || !array_is_list($group['conditions'])
                || count($group['conditions']) < 1 || count($group['conditions']) > 100) {
                throw new \InvalidArgumentException($error);
            }
            foreach ($group['conditions'] as $condition) {
                if (!is_array($condition) || !isset($condition['question'], $condition['operator'], $condition['value'])
                    || !is_string($condition['question']) || !is_string($condition['operator']) || !is_string($condition['value']) || mb_strlen($condition['value']) > 1024) {
                    throw new \InvalidArgumentException($error);
                }
                if ($condition['question'] === '0' && $condition['operator'] === '=' && $condition['value'] === '' && count($group['conditions']) === 1) {
                    continue;
                }
                $q = $questions[$condition['question']] ?? null;
                if (!$q || !$q['supported'] || !in_array($condition['operator'], $q['operators'], true)
                    || ($q['choices'] && !in_array($condition['value'], array_column($q['choices'], 'value'), true))
                    || ($q['numeric'] && !is_numeric($condition['value']))
                    || $condition['value'] === '') {
                    throw new \InvalidArgumentException($error);
                }
            }
        }
        return $groups;
    }

    public function stage($value, DataContainer $dc): string
    {
        self::$pending[(int) $dc->id] = self::validate((string) $value, (int) $dc->id);
        return ''; // Keep the existing varchar(1) page column empty.
    }

    public function save(DataContainer $dc): void
    {
        $page = (int) $dc->id;
        if (!array_key_exists($page, self::$pending)) {
            return;
        }
        $groups = self::$pending[$page];
        unset(self::$pending[$page]);
        if ($groups === self::groups(self::rows($page))) {
            return;
        }
        self::db()->transactional(static function ($db) use ($page, $groups) {
            $db->delete('tl_survey_condition', ['pid' => $page]);
            foreach ($groups as $index => $group) {
                foreach ($group['conditions'] as $condition) {
                    $db->insert('tl_survey_condition', [
                        'pid' => $page, 'tstamp' => time(), 'grp' => $index + 1,
                        'qid' => (int) $condition['question'], 'pageid' => (int) $group['target'],
                        'relation' => $condition['operator'], $db->quoteIdentifier('condition') => $condition['value'],
                    ]);
                }
            }
        });
    }
}
