<?php 

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class FormMatrixQuestion
 *
 * Form field "matrix question".
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class FormMatrixQuestion extends FormQuestionWidget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_matrix';
	protected $arrRows = array();
	protected $arrColumns = array();
	protected $blnNeutralColumn = false;
	protected $strNeutralColumn = "";
	protected $blnBipolar = false;
	protected $strAdjective1 = "";
	protected $strAdjective2 = "";
	protected $strBipolarPosition = "top";

	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'surveydata':
				parent::__set($strKey, $varValue);
				$this->strClass = "matrix";
				$this->arrRows = deserialize($varValue["matrixrows"]);
				if (!is_array($this->arrRows)) $this->arrRows = array();
				$this->arrColumns = deserialize($varValue["matrixcolumns"]);
				if (!is_array($this->arrColumns)) $this->arrColumns = array();
				$this->questiontype = $varValue['matrix_subtype'];
				$this->blnNeutralColumn = ($varValue["addneutralcolumn"]) ? true : false;
				$this->blnBipolar = ($varValue["addbipolar"]) ? true : false;
				$this->strNeutralColumn = $varValue["neutralcolumn"];
				$this->strAdjective1 = $varValue["adjective1"];
				$this->strAdjective2 = $varValue["adjective2"];
				$this->strBipolarPosition = $varValue["bipolarposition"];
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'addneutralcolumn':
				return $this->blnNeutralColumn;
				break;
			case 'neutralcolumn':
				return $this->strNeutralColumn;
				break;
		}
		return parent::__get($strKey);
	}
	
	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$submit = $this->getPost("question");
		$value = array();
		$value = $submit[$this->id];
		$varInput = $this->validator($value);
		$this->value = $varInput;
	}

	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if ((strcmp($this->questiontype, "matrix_singleresponse") == 0) && $this->mandatory && !is_array($varInput))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_matrix'], $this->title));
			return $varInput;
		}
		if (count($varInput) != count($this->arrRows) && $this->mandatory)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_matrix'], $this->title));
			return $varInput;
		}
		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$col_classes = array();
		$columncounter = 1;
		foreach ($this->arrColumns as $column)
		{
			$col_classes[$columncounter] = substr(standardize($column), 0, 28);
			$columncounter++;
		}
		if ($this->blnBipolar)
		{
			$col_classes['leftadjective'] = substr(standardize($this->strAdjective1), 0, 28);
			$col_classes['rightadjective'] = substr(standardize($this->strAdjective2), 0, 28);
		}
		if ($this->blnNeutralColumn)
		{
			$col_classes['neutral'] = substr(standardize($this->strNeutralColumn), 0, 28);
		}
		$template = new FrontendTemplate('survey_question_matrix');
		$template->nrOfColumns = max(1, count($this->arrColumns)) + (($this->blnNeutralColumn) ? 1 : 0) + (($this->blnBipolar && strcmp($this->strBipolarPosition, 'aside') == 0) ? 2 : 0);
		$template->columns = $this->arrColumns;
		$template->col_classes = $col_classes;
		$template->rows = $this->arrRows;
		$template->rowWidth = "40%";
		$template->colWidth = floor(60.0 / ($template->nrOfColumns * 1.0)) . "%";
		$template->bipolar = $this->blnBipolar;
		$template->bipolarTop = strcmp($this->strBipolarPosition, 'top') == 0;
		$template->bipolarAside = strcmp($this->strBipolarPosition, 'aside') == 0;
		$template->leftadjective = specialchars($this->strAdjective1);
		$template->rightadjective = specialchars($this->strAdjective2);
		$template->hasNeutralColumn = $this->blnNeutralColumn;
		$template->neutralColumn = specialchars($this->strNeutralColumn);
		$template->singleResponse = strcmp($this->questiontype, "matrix_singleresponse") == 0;
		$template->multipleResponse = !$template->singleResponse;
		$template->ctrl_name = specialchars($this->strName);
		$template->ctrl_id = specialchars($this->strId);
		$template->ctrl_class = (strlen($this->strClass) ? ' ' . $this->strClass : '');
		$template->values = $this->varValue;
		$widget = $template->parse();
		$widget .= $this->addSubmit();
		return $widget;
	}

	/**
	 * Create a string representation of the question result
	 * @return string
	 */
	public function getResultStringRepresentation()
	{
		$result = "";

		$rowcounter = 1;
		foreach ($this->arrRows as $row)
		{
			$choices = array();
			$columncounter = 1;
			$foundvalues = is_array($this->varValue[$rowcounter]) ? $this->varValue[$rowcounter] : array();
			foreach ($this->arrColumns as $column)
			{
				if (strcmp($this->questiontype, "matrix_singleresponse") == 0)
				{
					if ($this->varValue[$rowcounter] == $columncounter)
					{
						array_push($choices, $column);
					}
				}
				else
				{
					if (in_array($columncounter, $foundvalues))
					{
						array_push($choices, $column);
					}
				}
				$columncounter++;
			}
			if ($this->blnNeutralColumn)
			{
				if (strcmp($this->questiontype, "matrix_singleresponse") == 0)
				{
					if ($this->varValue[$rowcounter] == $columncounter)
					{
						array_push($choices, $this->strNeutralColumn);
					}
					else
					{
						if (in_array($columncounter, $foundvalues))
						{
							array_push($choices, $this->strNeutralColumn);
						}
					}
				}
			}
			if (count($choices))
			{
				$result .= $row . ": " . join($choices, ', ') . "\n";
			}
			$rowcounter++;
		}
		return $result;
	}
}

?>