<?php

namespace Hschottm\SurveyBundle;

/**
 * Class FormMultipleChoiceQuestion
 *
 * Form field "multiple choice question".
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class FormMultipleChoiceQuestion extends FormQuestionWidget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_multiplechoice';
	protected $strOtherTitle = "";
	protected $blnOther = false;
	protected $strStyle = false;
	protected $arrChoices = array();

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
				$this->strClass = "mc" . ((strlen($varValue['cssClass']) ? (" " . $varValue['cssClass']) : ""));
				$this->strOtherTitle = $varValue['othertitle'];
				$this->blnOther = ($varValue['addother']) ? true : false;
				$this->strStyle = $varValue['mc_style'];
				$this->arrChoices = deserialize($varValue["choices"]);
				if (!is_array($this->arrChoices)) $this->arrChoices = array();
				$this->questiontype = $varValue['multiplechoice_subtype'];
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
			case 'empty':
				$found = (is_array($this->varValue["value"])) ? (count($this->varValue["value"]) > 0) : false;
				if (!$found) $found = (!is_array($this->varValue["value"])) ? (strlen($this->varValue["value"]) > 0) : false;
				if (!$found) $found = strlen($this->varValue["other"]) > 0;
				return (!$found) ? true : false;
				break;
			default:
				return parent::__get($strKey);
				break;
		}
	}

	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$submit = $this->getPost("question");
		$submit_other = $this->getPost("other_question");
		$value = array();
		$value["value"] = $submit[$this->id];
		$value["other"] = $submit_other[$this->id];
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
		if (((strcmp($this->questiontype, "mc_singleresponse") == 0) || (strcmp($this->questiontype, "mc_dichotomous") == 0)) && $this->mandatory && !strlen($varInput["value"]))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
			return $varInput;
		}
		else if ((strcmp($this->questiontype, "mc_multipleresponse") == 0) && $this->mandatory && (!is_array($varInput["value"]) || count($varInput["value"]) == 0))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_mr'], $this->title));
			return $varInput;
		}

		if ((strcmp($this->questiontype, "mc_singleresponse") == 0))
		{
			if (($varInput["value"] == count($this->arrChoices) + 1) && (strlen($varInput["other"]) == 0))
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['missing_other_value'], $this->title));
				return $varInput;
			}
			if ($varInput["value"] == 0 && $this->mandatory)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
				return $varInput;
			}
		}
		else if ((strcmp($this->questiontype, "mc_dichotomous") == 0))
		{
			if ($varInput["value"] == 0)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
				return $varInput;
			}
		}
		else if ((strcmp($this->questiontype, "mc_multipleresponse") == 0))
		{
			if (is_array($varInput["value"]))
			{
				if ((in_array(count($this->arrChoices) + 1, $varInput["value"])) && (strlen($varInput["other"]) == 0))
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['missing_other_value'], $this->title));
					return $varInput;
				}
			}
		}
		return $varInput;
	}

	public function generateLabel()
	{
		return '';
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strOptions = '';

		$this->loadLanguageFile('tl_survey_question');
		$template = new \FrontendTemplate('survey_question_multiplechoice');
		$template->ctrl_name = \StringUtil::specialchars($this->strName);
		$template->ctrl_id = \StringUtil::specialchars($this->strId);
		$template->ctrl_class = (strlen($this->strClass) ? ' ' . $this->strClass : '');
		$template->singleResponse = strcmp($this->questiontype, "mc_singleresponse") == 0;
		$template->multipleResponse = strcmp($this->questiontype, "mc_multipleresponse") == 0;
		$template->dichotomous = strcmp($this->questiontype, "mc_dichotomous") == 0;
		$template->styleHorizontal = strcmp($this->strStyle, "horizontal") == 0;
		$template->styleVertical = strcmp($this->strStyle, "vertical") == 0;
		$template->styleSelect = strcmp($this->strStyle, "select") == 0;
		$template->values = $this->varValue;
		$template->choices = $this->arrChoices;
		$template->blnOther = $this->blnOther;
		$template->lngYes = $GLOBALS['TL_LANG']['tl_survey_question']['yes'];
		$template->lngNo = $GLOBALS['TL_LANG']['tl_survey_question']['no'];
		$template->otherTitle = \StringUtil::specialchars($this->strOtherTitle);
		$strOptions = $template->parse();
		$strError = $this->getErrorAsHTML();

		if ($this->hasLabel())
		{
			return sprintf('<fieldset id="ctrl_%s" class="radio_container%s"><div><label>%s%s%s</label></div>%s<input type="hidden" name="%s" value=""%s%s</fieldset>',
							$this->strId,
							(($this->strClass != '') ? ' ' . $this->strClass : ''),
							($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].' </span>' : ''),
							$this->title,
							($this->mandatory ? '<span class="mandatory">*</span>' : ''),
							$strError,
							$this->strName,
							$this->strTagEnding,
							$strOptions) . $this->addSubmit();
		}
		else
		{
	        return sprintf('<fieldset id="ctrl_%s" class="radio_container%s">%s<input type="hidden" name="%s" value=""%s%s</fieldset>',
							$this->strId,
							(($this->strClass != '') ? ' ' . $this->strClass : ''),
							$strError,
							$this->strName,
							$this->strTagEnding,
							$strOptions) . $this->addSubmit();
		}
	}

	public function generateWithError($blnSwitchOrder=false)
	{
		return $this->generate();
	}

	/**
	 * Create a string representation of the question result
	 * @return string
	 */
	public function getResultStringRepresentation()
	{
		$result = "";
		$choices = array();
		$counter = 1;
		foreach ($this->arrChoices as $choice)
		{
			if ($this->varValue["value"] == $counter)
			{
				array_push($choices, $choice);
			}
			$counter++;
		}
		if ($this->blnOther)
		{
			if ($this->varValue["value"] == $counter)
			{
				array_push($choices, $this->varValue["other"]);
			}
		}
		if (count($choices))
		{
			$result .= join($choices, ", ") . "\n";
		}
		return $result;
	}
}
