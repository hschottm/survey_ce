<?php

namespace Hschottm\SurveyBundle;

/**
 * Class SurveyQuestionMatrix
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionMatrix extends SurveyQuestion
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
			$objResult = \Database::getInstance()->prepare("SELECT * FROM tl_survey_result WHERE qid=? AND pid=?")
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
				foreach ($arrAnswer as $row => $answervalue)
				{
					if (is_array($answervalue))
					{
						foreach ($answervalue as $singleanswervalue)
						{
							$cumulated[$row][$singleanswervalue]++;
						}
					}
					else
					{
						$cumulated[$row][$answervalue]++;
					}
				}
			}
		}
		$this->arrStatistics['cumulated'] = $cumulated;
	}

	public function getAnswersAsHTML()
	{
		if (is_array($this->statistics["cumulated"]))
		{
			$template = new \FrontendTemplate('survey_answers_matrix');
			$template->choices = deserialize($this->arrData['matrixcolumns'], true);
			$template->rows = deserialize($this->arrData['matrixrows'], true);
			$template->statistics = $this->statistics;
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
			$arrRows = deserialize($this->arrData['matrixrows'], true);
			$arrChoices = deserialize($this->arrData['matrixcolumns'], true);
			$row_counter = 1;
			foreach ($arrRows as $id => $rowdata)
			{
				array_push($result, array("sheetname" => $sheet,"row" => $row + $row_counter, "col" => 1, "data" => utf8_decode($rowdata), "fontweight" => XLSFONT_BOLD));
				$row_counter++;
			}

			$row_counter = 1;
			foreach ($arrRows as $id => $rowdata)
			{
				$col_counter = 1;
				foreach ($arrChoices as $choiceid => $choice)
				{
					if ($row_counter == 1) array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => 1 + $col_counter, "data" => utf8_decode($choice), "fontweight" => XLSFONT_BOLD));
					array_push($result, array("sheetname" => $sheet,"row" => $row + $row_counter, "col" => 1 + $col_counter, "data" => (($this->statistics['cumulated'][$row_counter][$col_counter]) ? $this->statistics['cumulated'][$row_counter][$col_counter] : 0), "type" => CELL_FLOAT));
					$col_counter++;
				}
				$row_counter++;
			}

			$row += count($arrRows);
		}
		$row += 2;
		return $result;
	}
}
