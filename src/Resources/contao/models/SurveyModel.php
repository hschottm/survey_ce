<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\Database;
use Contao\Model;
use Contao\StringUtil;

/**
 * @property bool $useResultCategories
 * @property string $resultCategories
 */
class SurveyModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey';

    protected static $questionMapper = [];

    public static function findByQuestionId(int $questionId): ?self
    {
        if (isset(static::$questionMapper[$questionId])) {
            return static::findByPk(static::$questionMapper[$questionId]);
        }

        $result = Database::getInstance()->prepare(
            "SELECT tl_survey.id FROM tl_survey "
            ."JOIN tl_survey_page ON tl_survey_page.pid = tl_survey.id "
            ."JOIN tl_survey_question ON tl_survey_question.pid = tl_survey_page.id "
            ."WHERE tl_survey_question.id=?"
        )->execute($questionId);

        $surveyModel = static::findByPk($result->id ?? 0);
        if ($surveyModel) {
            static::$questionMapper[$questionId] = $surveyModel->id;
        }

        return $surveyModel;

    }

    public function getCategoryName(int $id): string
    {
        $categories = StringUtil::deserialize($this->resultCategories, true);
        $categories = array_column($categories, 'category', 'id');
        return ($categories[$id] ?? '');
    }
}

class_alias(SurveyModel::class, 'SurveyModel');
