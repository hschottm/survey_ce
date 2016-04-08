<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyQuestionOpenended
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionOpenended extends SurveyQuestion
{
	/**
	 * Import String library
	 */
	public function __construct($question_id = 0)
	{
		parent::__construct($question_id);
	}
	
	public function resultAsString($res)
	{
		return $res;
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
			}
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
		$col = 1;
		if (is_array($this->statistics["answers"]))
		{
			foreach ($this->statistics["answers"] as $answer) 
			{
				array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => $col++, "data" => utf8_decode($answer))); 
			}
		}
		$row += 2;
		return $result;
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
}

