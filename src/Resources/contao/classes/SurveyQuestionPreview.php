<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

/**
 * Class SurveyQuestionPreview.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionPreview extends \Contao\Backend
{
    /**
     * Import String library.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Compile format definitions and return them as string.
     *
     * @param array
     * @param bool
     * @param mixed $row
     * @param mixed $blnWriteToFile
     *
     * @return string
     */
    public function compilePreview($row, $blnWriteToFile = false)
    {
        $widget = '';
        $strClass = $GLOBALS['TL_SVY'][$row['questiontype']];
        if (class_exists($strClass)) {
            $objWidget = new $strClass();
            $objWidget->surveydata = $row;
            $widget = $objWidget->generate();
        }

        $template = new \Contao\FrontendTemplate('be_survey_question_preview');
        $template->hidetitle = $row['hidetitle'];
        $template->help = \Contao\StringUtil::specialchars($row['help']);
        $template->questionNumber = $this->getQuestionNumber($row);
        $template->title = \Contao\StringUtil::specialchars($row['title']);
        $template->obligatory = $row['obligatory'];
        $template->question = $row['question'];
        $return = $template->parse();
        $return .= $widget;

        return $return;
    }

    protected function getQuestionNumber($row)
    {
        $surveyQuestionCollection = \Hschottm\SurveyBundle\SurveyQuestionModel::findBy(['pid=?', 'sorting<=?'], [$row['pid'], $row['sorting']]);

        return (null != $surveyQuestionCollection) ? $surveyQuestionCollection->count() : 0;
    }
}
