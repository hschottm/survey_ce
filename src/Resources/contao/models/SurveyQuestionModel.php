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
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\System;

/**
 * @property int $id
 * @property int $pid
 * @property string $questiontype
 * @property string $title
 * @property string $question
 * @property bool $hidetitle
 * @property string $choices
 *
 * @method static SurveyQuestionModel|null findByPk($val, array $opt=array())
 */
class SurveyQuestionModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey_question';

    public function findSurveyPageTitleAndQuestionById($id)
    {
        $framework = System::getContainer()->get('contao.framework');
        /** @var SurveyQuestionModel $questionModel */
        $questionModel = $framework->getAdapter(static::class)->findByPk($id);
        if (null == $questionModel) {
            return null;
        }
        $result = $questionModel->row();
        $pageModel = $framework->getAdapter(SurveyPageModel::class)->findByPk($questionModel->pid);
        if (null != $pageModel) {
            $result['pagetitle'] = $pageModel->title;
            $result['parentID'] = $pageModel->pid;
        }

        return $result;
    }

    public static function findBySurvey(int $id): ?Collection
    {
        $result = Database::getInstance()
            ->prepare('SELECT tl_survey_question.* FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
            ->execute($id);
        if ($result->count() < 1) {
            return null;
        }

        return Collection::createFromDbResult($result, static::$strTable);
    }

    public function getCategoryByChoice(int $choice): ?int
    {
        $choices = StringUtil::deserialize($this->choices, true);
        if (isset($choices[$choice]['category']) && (!(empty($choices[$choice]['category']))
                || 0 === $choices[$choice]['category']
                || "0" === $choices[$choice]['category']))
        {
            return $choices[$choice]['category'];
        }
        return null;
    }
}

class_alias(SurveyQuestionModel::class, 'SurveyQuestionModel');
