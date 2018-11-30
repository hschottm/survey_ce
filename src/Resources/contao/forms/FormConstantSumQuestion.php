<?php

namespace Hschottm\SurveyBundle;

/**
 * Class FormConstantSumQuestion
 *
 * Form field "multiple choice question".
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class FormConstantSumQuestion extends FormQuestionWidget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_constantsum';
	protected $strSumOption = "exact";
	protected $dblSum = 100;
	protected $arrChoices = array();
	protected $blnInputFirst = false;

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
				$this->strClass = "constantsum" . ((strlen($varValue['cssClass']) ? (" " . $varValue['cssClass']) : ""));
				$this->strSumOption = $varValue['sumoption'];
				$this->dblSum = $varValue['sum'];
				$this->blnInputFirst = ($varValue['inputfirst']) ? true : false;
				$this->arrChoices = deserialize($varValue["sumchoices"]);
				if (!is_array($this->arrChoices)) $this->arrChoices = array();
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
		return parent::__get($strKey);
	}

	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$submit = $this->getPost("question");
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
		if (!is_array($varInput) || count($varInput) == 0)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_constantsum'], $this->title));
			return $varInput;
		}
		$sum = 0.0;
		foreach ($varInput as $value)
		{
			if (strlen($value) == 0)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_constantsum'], $this->title));
				return $varInput;
			}
			$sum += $value;
		}
		switch ($this->strSumOption)
		{
			case 'exact':
				if ($sum != $this->dblSum)
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['sumnotexact'], $this->title, $this->dblSum));
					return $varInput;
				}
				break;
			case 'max':
				if ($sum > $this->dblSum)
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['sumnotmax'], $this->title, $this->dblSum));
					return $varInput;
				}
				break;
		}
		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->loadLanguageFile('tl_survey_question');
		$template = new FrontendTemplate('survey_question_constantsum');
		$template->choices = $this->arrChoices;
		$template->blnInputFirst = $this->blnInputFirst;
		$template->name = specialchars($this->strName);
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
		$choices = array();
		$counter = 1;
		foreach ($this->arrChoices as $choice)
		{
			if (strlen($this->varValue[$counter]))
			{
				$result .= $choice . ": " . $this->varValue[$counter] . "\n";
			}
			$counter++;
		}
		return $result;
	}
}
