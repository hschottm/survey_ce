<?php

namespace Hschottm\SurveyBundle;

/**
 * Class SurveyQuestionPreview
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionPreview extends \Backend
{

	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();
	}

	protected function getQuestionNumber($row)
	{
    $surveyQuestionCollection = \Hschottm\SurveyBundle\SurveyQuestionModel::findBy(['pid=?', 'sorting<=?'], [$row["pid"], $row["sorting"]]);
    return (null != $surveyQuestionCollection) ? $surveyQuestionCollection->count() : 0;
	}

	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	public function compilePreview($row, $blnWriteToFile=false)
	{
		$widget = "";
		$strClass = $GLOBALS['TL_SVY'][$row['questiontype']];
		if ($this->classFileExists($strClass))
		{
			$objWidget = new $strClass();
			$objWidget->surveydata = $row;
			$widget = $objWidget->generate();
		}

		$template = new \FrontendTemplate('be_survey_question_preview');
		$template->hidetitle = $row['hidetitle'];
		$template->help = \StringUtil::specialchars($row['help']);
		$template->questionNumber = $this->getQuestionNumber($row);
		$template->title = \StringUtil::specialchars($row['title']);
		$template->obligatory = $row['obligatory'];
		$template->question = $row['question'];
		$return = $template->parse();
		$return .= $widget;
		return $return;
	}

}
