<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyQuestionPreview
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionPreview extends Backend
{

	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('String');
	}
	
	protected function getQuestionNumber($row)
	{
		$objElements = $this->Database->prepare("SELECT * FROM tl_survey_question WHERE (pid=? AND sorting <= ?)")
			->execute($row["pid"], $row["sorting"]);
		return $objElements->numRows;
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

		$template = new FrontendTemplate('be_survey_question_preview');
		$template->hidetitle = $row['hidetitle'];
		$template->help = specialchars($row['help']);
		$template->questionNumber = $this->getQuestionNumber($row);
		$template->title = specialchars($row['title']);
		$template->obligatory = $row['obligatory'];
		$template->question = $row['question'];
		$return = $template->parse();
		$return .= $widget;
		return $return;
	}

}

?>