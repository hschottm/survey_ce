<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\Model;

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
}

class_alias(SurveyQuestionModel::class, 'SurveyQuestionModel');
