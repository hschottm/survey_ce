<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    survey_ce
 * @license    LGPL
 */


/**
 * Class SurveyQuestion
 *
 * Provide methods to handle import and export of member data.
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
abstract class SurveyQuestion extends \Backend
{
	protected $arrData;
	protected $arrStatistics;
	
	/**
	 * Import String library
	 */
	public function __construct($question_id = 0)
	{
		parent::__construct();
		$this->Database = \Database::getInstance(); // fix: in the backend this variable is empty but $this->arrObjects['Database'] is an object???
		$this->loadLanguageFile("tl_survey_question");
		$this->loadLanguageFile("tl_survey_result");
		$this->objQuestion = NULL;
		$this->arrStatistics = array();
		$this->arrStatistics["answered"] = 0;
		$this->arrStatistics["skipped"] = 0;
		if ($question_id > 0)
		{
			$objQuestion = $this->Database->prepare("SELECT tl_survey_question.*, tl_survey_page.title pagetitle, tl_survey_page.pid parentID FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_question.id = ?")
				->execute($question_id);
			if ($objQuestion->numRows)
			{
				$this->data = $objQuestion->fetchAssoc();
			}
		}
	}
	
	protected abstract function calculateStatistics();
	
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
				$this->arrStatistics["answered"]++;
			}
			else
			{
				$this->arrStatistics["skipped"]++;
			}
		}
	}
	
	public function getAnswersAsHTML()
	{
		if (is_array($this->statistics["answers"]))
		{
			$template = new FrontendTemplate('survey_answers_default');
			$template->answers = $this->statistics['answers'];
			return $template->parse();
		}
	}
	
	public function __set($name, $value) 
	{
		switch ($name)
		{
			case "data":
				if (is_array($value))
				{
					$this->arrData =& $value;
				}
				break;
			default:
				$this->$name = $value;
				break;
		}
	}
	
	public function clearStatistics()
	{
		$this->arrStatistics = array();
	}

	public function __get($name) 
	{
		switch ($name)
		{
			case "statistics":
				if (count($this->arrStatistics) <= 2) $this->calculateStatistics();
				return $this->arrStatistics;
				break;
			case "id":
			case "title":
			case "question":
			case "questiontype":
				return $this->arrData[$name];
				break;
			case "titlebgcolor":
				return "#C0C0C0";
			case "titlecolor":
				return "#000000";
			case "otherbackground":
				return "#FFFFCC";
			case "othercolor":
				return "#000000";
			default:
				return $this->$name;
				break;
		}
	}

	public function exportDataToExcel($sheet, &$row)
	{
		// overwrite in parent classes
		return array();
	}
	
	/**
	* Calculate the Excel cell address (A,...,Z,AA,AB,...) from a numeric index
	*
	*/
	protected function getCellTitle($index)
	{
		$alphabet = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		if ($index < 26) return $alphabet[$index];
		return $alphabet[floor($index / 26)-1] . $alphabet[$index-(floor($index / 26)*26)];
	}
}

