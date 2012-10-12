<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class FormOpenEndedQuestion
 *
 * Form field "open-ended question".
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class FormOpenEndedQuestion extends FormQuestionWidget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_openended';

	protected $strTextBefore = "";
	protected $strTextAfter = "";
	protected $strLowerBound = "";
	protected $strUpperBound = "";

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
				$this->strClass = "openended";
				$this->strTextBefore = $varValue['openended_textbefore'];
				$this->strTextAfter = $varValue['openended_textafter'];
				$this->questiontype = $varValue['openended_subtype'];
				switch ($this->questiontype)
				{
					case "oe_integer":
					case "oe_float":
						$this->rgxp = "digit";
						$this->strLowerBound = $varValue['lower_bound'];
						$this->strUpperBound = $varValue['upper_bound'];
						break;
					case "oe_date":
						$this->rgxp = "date";
						$this->strLowerBound = $varValue['lower_bound_date'];
						$this->strUpperBound = $varValue['upper_bound_date'];
						break;
					case "oe_time":
						$this->rgxp = "time";
						$this->strLowerBound = $varValue['lower_bound_time'];
						$this->strUpperBound = $varValue['upper_bound_time'];
						break;
				}
				$method = "setData_" . $varValue['openended_subtype'];
				if (method_exists($this, $method)) $this->$method($varValue);
				break;
			case 'maxlength':
				$this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
				break;

			case 'value':
				$this->varValue = $varValue;
				if (strcmp($this->questiontype, 'oe_multiline') != 0) $this->arrAttributes["value"] = $varValue;

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
		return parent::__get($strKey);
	}
	
	protected function setData_oe_singleline($varValue)
	{
		if (strlen($varValue['openended_width'])) $this->arrAttributes["size"] = specialchars($varValue['openended_width']);
		if (strlen($varValue['openended_textinside'])) $this->arrAttributes["value"] = specialchars($varValue['openended_textinside']);
		if (strlen($this->varValue)) $this->arrAttributes["value"] = specialchars($this->varValue);
	}

	protected function setData_oe_integer($varValue)
	{
		$this->setData_oe_singleline($varValue);
	}

	protected function setData_oe_float($varValue)
	{
		$this->setData_oe_singleline($varValue);
	}

	protected function setData_oe_date($varValue)
	{
		$this->setData_oe_singleline($varValue);
	}

	protected function setData_oe_time($varValue)
	{
		$this->setData_oe_singleline($varValue);
	}

	protected function setData_oe_multiline($varValue)
	{
		if (strlen($varValue['openended_rows'])) $this->arrAttributes["rows"] = specialchars($varValue['openended_rows']);
		if (strlen($varValue['openended_cols'])) $this->arrAttributes["cols"] = specialchars($varValue['openended_cols']);
		if (!strlen($this->varValue)) if (strlen($varValue['openended_textinside'])) $this->varValue = $varValue['openended_textinside'];
	}

	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$submit = $this->getPost("question");
		$varInput = $this->validator(deserialize($submit[$this->id]));

		//if (!$this->hasErrors())
		//{
			$this->value = $varInput;
		//}
	}

	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$oldlabel = $this->label;
		$label = (strlen($this->label)) ? $this->label : $this->title;
		$this->label = $label;
		if (is_array($varInput))
		{
			$result = parent::validator($varInput);
		}
		else
		{
			$result = parent::validator(trim($varInput));
			$result = $this->check_bounds($result);
		}
		$this->label = $oldlabel;
		return $result;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$template = new FrontendTemplate('survey_question_openended');
		$template->ctrl_name = specialchars($this->strName);
		$template->ctrl_id = specialchars($this->strId);
		$template->ctrl_class = (strlen($this->strClass) ? ' ' . $this->strClass : '');
		$template->multiLine = (strcmp($this->questiontype, "oe_multiline") == 0);
		$template->singleLine = (strcmp($this->questiontype, "oe_singleline") == 0);
		$template->value = $this->varValue;
		$template->textBefore = $this->strTextBefore;
		$template->textAfter = $this->strTextAfter;
		$template->attributes = $this->getAttributes();
		$widget = $template->parse();
		$widget .= $this->addSubmit();
		return $widget;
	}

	/**
	 * Validates certain fields against lower/upper bounds.
	 * @param string
	 * @return string
	 */
	protected function check_bounds($varInput)
	{
		if ($this->hasErrors() || !strlen($varInput))
		{
			// Don't check any further, value might not be a valid string to be compared against bounds
			return $varInput;
		}

		$result = $varInput;


		if (strlen($this->strLowerBound))
		{
			$strErrMsg = $GLOBALS['TL_LANG']['ERR']['lower_bound'];
			$lower = intval($this->strLowerBound);

			switch ($this->questiontype)
			{
				case 'oe_integer':
					$value = intval($varInput);
					if ($value < $lower)
					{
						$this->addError(sprintf($strErrMsg, $value, $this->label, $lower));
					}
					break;

				case 'oe_float':
					$lower = floatval($this->strLowerBound);
					$value = floatval($varInput);
					if ($value < $lower)
					{
						$this->addError(sprintf($strErrMsg, $value, $this->label, $lower));
					}
					break;


				case 'oe_date':
					// $varInput is a string like '25.12.2009', use the Date class to get the comparable timestamp.
					// This is not well documented in .../Date.php
					$objDateValue = new Date($varInput, $GLOBALS['TL_CONFIG']['dateFormat']);
					$value = $objDateValue->timestamp;
					if ($value < $lower)
					{
						$objDateLower = new Date($lower, $GLOBALS['TL_CONFIG']['dateFormat']);
						$this->addError(sprintf($strErrMsg, $objDateValue->date, $this->label, $objDateLower->date));
					}
					// correct valid inputs like 31.11.2009 to 01.12.2009
					$result = $objDateValue->date;
					break;

				case 'oe_time':
					$objDateValue = new Date($varInput, $GLOBALS['TL_CONFIG']['timeFormat']);
					$value = $objDateValue->timestamp;
					if ($value < $lower)
					{
						$objDateLower = new Date($lower, $GLOBALS['TL_CONFIG']['timeFormat']);
						$this->addError(sprintf($strErrMsg, $objDateValue->time, $this->label, $objDateLower->time));
					}
					// correct valid inputs like 13:59:xyz etc to 13:59
					$result = $objDateValue->time;
					break;
			}
		}

		if (strlen($this->strUpperBound))
		{
			$strErrMsg = $GLOBALS['TL_LANG']['ERR']['upper_bound'];
			$upper = intval($this->strUpperBound);

			switch ($this->questiontype)
			{
				case 'oe_integer':
					$value = intval($varInput);
					if ($value > $upper)
					{
						$this->addError(sprintf($strErrMsg, $value, $this->label, $upper));
					}
					break;

				case 'oe_float':
					$upper = floatval($this->strUpperBound);
					$value = floatval($varInput);
					if ($value > $upper)
					{
						$this->addError(sprintf($strErrMsg, $value, $this->label, $upper));
					}
					break;

				case 'oe_date':
					$objDateValue = new Date($varInput, $GLOBALS['TL_CONFIG']['dateFormat']);
					$value = $objDateValue->timestamp;
					if ($value > $upper)
					{
						$objDateUpper = new Date($upper, $GLOBALS['TL_CONFIG']['dateFormat']);
						$this->addError(sprintf($strErrMsg, $objDateValue->date, $this->label, $objDateUpper->date));
					}
					// correct valid inputs like 31.11.2009 to 01.12.2009
					$result = $objDateValue->date;
					break;

				case 'oe_time':
					$objDateValue = new Date($varInput, $GLOBALS['TL_CONFIG']['timeFormat']);
					$value = $objDateValue->timestamp;
					if ($value > $upper)
					{
						$objDateUpper = new Date($upper, $GLOBALS['TL_CONFIG']['timeFormat']);
						$this->addError(sprintf($strErrMsg, $objDateValue->time, $this->label, $objDateUpper->time));
					}
					// correct valid inputs like 13:59:xyz etc to 13:59
					$result = $objDateValue->time;
					break;
			}
		}

		return $result;
	}
}

?>