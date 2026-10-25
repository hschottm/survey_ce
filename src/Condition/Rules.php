<?php

namespace Hschottm\SurveyBundle\Condition;

/** Evaluation shared by the frontend and the backend's condition vocabulary. */
final class Rules
{
    public static function evaluate(array $rows, callable $answer, callable $question): ?int
    {
        usort($rows, static fn ($a, $b) => [(int) $a['grp'], (int) $a['id']] <=> [(int) $b['grp'], (int) $b['id']]);
        $groups = [];
        foreach ($rows as $row) {
            $groups[$row['grp']][] = $row;
        }
        foreach ($groups as $group) {
            $matches = true;
            foreach ($group as $row) {
                // qid=0 is the existing unconditional jump representation.
                if ((int) $row['qid'] === 0) {
                    continue;
                }
                $q = $question((int) $row['qid']);
                $result = $answer((int) $row['qid']);
                $value = is_array($result) ? ($result['value'] ?? null) : $result;
                if (!$q || !self::matches($value, (string) $row['relation'], (string) $row['condition'], $q)) {
                    $matches = false;
                    break;
                }
            }
            if ($matches) {
                return (int) $group[0]['pageid'];
            }
        }

        return null;
    }

    public static function matches(mixed $value, string $relation, string $expected, array $question): bool
    {
        if ($value === null || $value === '' || $value === []) {
            return false;
        }
        $multipleChoice = ($question['questiontype'] ?? '') === 'multiplechoice';
        if ($multipleChoice && is_array($value)) {
            if (!in_array($relation, ['=', '!='], true)) {
                return false;
            }
            $selected = array_map('strval', array_filter($value, 'is_scalar'));
            $selected = array_filter($selected, static fn ($value) => ctype_digit($value) && (int) $value > 0);
            if (!$selected) {
                return false;
            }
            $contains = in_array($expected, $selected, true);
            return $relation === '=' ? $contains : !$contains;
        }
        if (!is_scalar($value) || ($multipleChoice && (string) $value === '0')) {
            return false;
        }
        $numeric = in_array($question['openended_subtype'] ?? '', ['oe_integer', 'oe_float'], true);
        if ($numeric || in_array($relation, ['>', '<', '>=', '<='], true)) {
            if (!is_numeric($value) || !is_numeric($expected)) {
                return false;
            }
            $value = (float) $value;
            $expected = (float) $expected;
        } else {
            $value = (string) $value;
        }
        return match ($relation) {
            '=' => $value === $expected,
            '!=' => $value !== $expected,
            '>' => $value > $expected,
            '<' => $value < $expected,
            '>=' => $value >= $expected,
            '<=' => $value <= $expected,
            default => false,
        };
    }
}
