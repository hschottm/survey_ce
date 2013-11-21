<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyQuestionMultiplechoice
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionMultiplechoice extends SurveyQuestion
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

	protected function calculateAnsweredSkipped(&$objResult)
	{
		$this->arrStatistics = array();
		$this->arrStatistics["answered"] = 0;
		$this->arrStatistics["skipped"] = 0;
		while ($objResult->next())
		{
			$id = (strlen($objResult->pin)) ? $objResult->pin : $objResult->uid;
			$this->arrStatistics["participants"][$id][] = $objResult->row();
			$this->arrStatistics["answers"][] = $objResult->result;
			if (strlen($objResult->result))
			{
				$arrAnswer = deserialize($objResult->result, true);
				$found = false;
				if (is_array($arrAnswer['value']))
				{
					foreach ($arrAnswer['value'] as $answervalue)
					{
						if (strlen($answervalue)) $found = true;
					}
				}
				else
				{
					if (strlen($arrAnswer["value"])) $found = true;
				}
				if (strlen($arrAnswer['other'])) $found = true;
				if ($found)
				{
					$this->arrStatistics["answered"]++;
				}
				else
				{
					$this->arrStatistics["skipped"]++;
				}
			}
			else
			{
				$this->arrStatistics["skipped"]++;
			}
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
	
	protected function calculateCumulated()
	{
		$cumulated = array();
		$cumulated['other'] = array();
		foreach ($this->arrStatistics["answers"] as $answer)
		{
			$arrAnswer = deserialize($answer, true);
			if (is_array($arrAnswer['value']))
			{
				foreach ($arrAnswer['value'] as $answervalue)
				{
					if (strlen($answervalue)) $cumulated[$answervalue]++;
				}
			}
			else
			{
				if (strlen($arrAnswer['value'])) $cumulated[$arrAnswer['value']]++;
			}
			if (strlen($arrAnswer['other']))
			{
				array_push($cumulated['other'], $arrAnswer['other']);
			}
		}
		$this->arrStatistics['cumulated'] = $cumulated;
	}

	public function getAnswersAsHTML()
	{
		if (is_array($this->statistics["cumulated"]))
		{
			$template = new FrontendTemplate('survey_answers_multiplechoice');
			$template->statistics = $this->statistics;
			$template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
			$template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
			$template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];
			$template->choices = (strcmp($this->arrData['multiplechoice_subtype'], 'mc_dichotomous') != 0) ? deserialize($this->arrData['choices'], true) : array(0 => $GLOBALS['TL_LANG']['tl_survey_question']['yes'], 1 => $GLOBALS['TL_LANG']['tl_survey_question']['no']);
			$template->other = ($this->arrData['addother']) ? true : false;
			$template->othertitle = specialchars($this->arrData['othertitle']);
			$otherchoices = array();
			if (count($this->statistics['cumulated']['other']))
			{
				foreach ($this->statistics['cumulated']['other'] as $value)
				{
					$otherchoices[specialchars($value)]++;
				}
			}
			$template->otherchoices = $otherchoices;
			return $template->parse();
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
		array_push($result, array("sheetname" => $sheet,"row" => $row+1, "col" => 0, "data" => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections']), "bgcolor" => $this->titlebgcolor, "color" => $this->titlecolor, "fontweight" => XLSFONT_BOLD));
		$arrChoices = (strcmp($this->arrData['multiplechoice_subtype'], 'mc_dichotomous') != 0) ? deserialize($this->arrData['choices'], true) : array(0 => $GLOBALS['TL_LANG']['tl_survey_question']['yes'], 1 => $GLOBALS['TL_LANG']['tl_survey_question']['no']);
		$col = 1;
		foreach ($arrChoices as $id => $choice)
		{
			array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($choice)));
			array_push($result, array("sheetname" => $sheet,"row" => $row+1, "col" => $col++, "data" => (($this->statistics['cumulated'][$id+1]) ? $this->statistics['cumulated'][$id+1] : 0), "type" => CELL_FLOAT));
		}
		if ($this->arrData['addother'])
		{
			array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($this->arrData['othertitle'])));
			array_push($result, array("sheetname" => $sheet,"row" => $row+1, "col" => $col++, "data" => count($this->statistics['cumulated']['other']), "type" => CELL_FLOAT));
			if (count($this->statistics['cumulated']['other']))
			{
				$otherchoices = array();
				foreach ($this->statistics['cumulated']['other'] as $value)
				{
					$otherchoices[$value]++;
				}
				foreach ($otherchoices as $key => $count)
				{
					array_push($result, array("sheetname" => $sheet,"row" => $row, "col" => $col, "data" => utf8_decode($key), "bgcolor" => $this->otherbackground, "color" => $this->othercolor));
					array_push($result, array("sheetname" => $sheet,"row" => $row+1, "col" => $col++, "data" => $count, "type" => CELL_FLOAT));
				}
			}
		}
		$row += 3;
		return $result;
	}

	
}

