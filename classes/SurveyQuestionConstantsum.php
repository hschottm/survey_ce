<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyQuestionConstantsum
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionConstantsum extends SurveyQuestion
{
	/**
	 * Import String library
	 */
	public function __construct($question_id = 0)
	{
		parent::__construct($question_id);
	}
	
	protected function calculateStatistics()
	{
		if (array_key_exists("id", $this->arrData) && array_key_exists("parentID", $this->arrData))
		{
			$objResult = $this->Database->prepare("SELECT * FROM tl_survey_result WHERE qid=? AND pid=?")
				->execute($this->arrData["id"], $this->arrData["parentID"]);
			if ($objResult->numRows)
			{
				$this->calculateAnsweredSkipped($objResult);
				$this->calculateCumulated();
			}
		}
	}

	protected function calculateCumulated()
	{
		$cumulated = array();
		$cumulated['other'] = array();
		foreach ($this->arrStatistics["answers"] as $answer)
		{
			$arrAnswer = deserialize($answer, true);
			if (is_array($arrAnswer))
			{
				foreach ($arrAnswer as $answerkey => $answervalue)
				{
					$cumulated[$answerkey][$answervalue]++;
				}
			}
		}
		foreach ($cumulated as $key => $value)
		{
			ksort($value);
			$cumulated[$key] = $value;
		}
		$this->arrStatistics['cumulated'] = $cumulated;
	}

	public function getAnswersAsHTML()
	{
		if (is_array($this->statistics["cumulated"]))
		{
			$template = new FrontendTemplate('survey_answers_constantsum');
			$template->choices = deserialize($this->arrData['sumchoices'], true);
			$template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
			$template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
			$template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];
			$template->cumulated = $this->statistics["cumulated"];
			return $template->parse();
		}
	}

	public function __set($name, $value) 
	{
		switch ($name)
		{
			default:
				parent::__set($name, $value);
				break;
		}
	}

	public function exportDataToExcel($sheet, &$row)
	{
		$result = array();
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => "ID", "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => $this->id, "type" => CELL_FLOAT));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0]), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype])));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question']['title'][0]), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => utf8_decode($this->title)));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question']['question'][0]), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => utf8_decode(strip_tags($this->question))));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question']['answered']), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => $this->statistics["answered"], "type" => CELL_FLOAT));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question']['skipped']), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1, "data" => $this->statistics["skipped"], "type" => CELL_FLOAT));
		$row++;
		array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['answers']), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));

		if (is_array($this->statistics["cumulated"]))
		{
			$arrChoices = deserialize($this->arrData['sumchoices'], true);
			$counter = 1;
			foreach ($arrChoices as $id => $choice)
			{
				array_push($result, array("sheetname" => $sheet,"row" => $row + $counter - 1, "col" => 1, "data" => utf8_decode($choice)));
				$counter += 2;
			}
			$counter = 1;
			$idx = 1;
			foreach ($arrChoices as $id => $choice)
			{
				$acounter = 2;
				foreach ($this->statistics["cumulated"][$idx] as $answervalue => $nrOfAnswers)
				{
					array_push($result, array("sheetname" => $sheet,"row" => $row + $counter - 1, "col" => $acounter, "data" => $answervalue, "type" => CELL_FLOAT));
					array_push($result, array("sheetname" => $sheet,"row" => $row + $counter, "col" => $acounter, "data" => (($nrOfAnswers) ? $nrOfAnswers : 0), "type" => CELL_FLOAT));
					$acounter++;
				}
				$idx++;
				$counter += 2;
			}

			$row += count($arrChoices) * 2 + 1;
		}
		return $result;
	}
}

