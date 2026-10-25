<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

/**
 * Class FormOpenEndedQuestion.
 *
 * Form field "open-ended question".
 *
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class FormOpenEndedQuestion extends FormQuestionWidget
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'form_openended';

    protected $strTextBefore = '';
    protected $strTextAfter = '';
    protected $strLowerBound = '';
    protected $strUpperBound = '';

    /**
     * Add specific attributes.
     *
     * @param string
     * @param mixed
     * @param mixed $strKey
     * @param mixed $varValue
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey) {
            case 'surveydata':
                parent::__set($strKey, $varValue);
                $this->strClass = 'openended'.((\strlen((string) $varValue['cssClass']) ? (' '.$varValue['cssClass']) : ''));
                $this->strTextBefore = $varValue['openended_textbefore'];
                $this->strTextAfter = $varValue['openended_textafter'];
                $this->questiontype = $varValue['openended_subtype'];
                switch ($this->questiontype) {
                    case 'oe_integer':
                    case 'oe_float':
                        $this->rgxp = 'digit';
                        $this->strLowerBound = $varValue['lower_bound'];
                        $this->strUpperBound = $varValue['upper_bound'];
                        break;
                    case 'oe_date':
                        $this->rgxp = 'date';
                        $this->strLowerBound = $varValue['lower_bound_date'];
                        $this->strUpperBound = $varValue['upper_bound_date'];
                        break;
                    case 'oe_time':
                        $this->rgxp = 'time';
                        $this->strLowerBound = $varValue['lower_bound_time'];
                        $this->strUpperBound = $varValue['upper_bound_time'];
                        break;
                }
                $method = 'setData_'.$varValue['openended_subtype'];
                if (method_exists($this, $method)) {
                    $this->$method($varValue);
                }
                break;
            case 'maxlength':
                $this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
                break;

            case 'value':
                $this->varValue = $varValue;
                if (0 != strcmp($this->questiontype, 'oe_multiline')) {
                    $this->arrAttributes['value'] = $varValue;
                }

                // no break
            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Return a parameter.
     *
     * @param mixed $strKey
     *
     * @throws Exception
     *
     * @return string
     */
    public function __get($strKey)
    {
        return parent::__get($strKey);
    }

    /**
     * Validate input and set value.
     */
    public function validate()
    {
        $submit = $this->getPost('question');
        $varInput = $this->validator($submit[$this->id] ?? '');

        //if (!$this->hasErrors())
        //{
        $this->value = $varInput;
        //}
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        $template = new \Contao\FrontendTemplate('survey_question_openended');
        $template->ctrl_name = $this->strName;
        $template->ctrl_id = $this->strId;
        $template->ctrl_class = (\strlen((string) $this->strClass) ? ' '.$this->strClass : '');
        $template->multiLine = (0 == strcmp($this->questiontype, 'oe_multiline'));
        $template->singleLine = (0 == strcmp($this->questiontype, 'oe_singleline'));
        $template->value = $this->varValue;
        $template->textBefore = $this->strTextBefore;
        $template->textAfter = $this->strTextAfter;
        $template->attributes = $this->getAttributes();
        $strError = $this->getErrorAsHTML();
        $template->blnError = (\strlen((string) $strError) ? true : false);
        $widget = $template->parse();

        return $widget;
    }

    protected function setData_oe_singleline($varValue)
    {
        if (\strlen((string) $varValue['openended_width'])) {
            $this->arrAttributes['size'] = \Contao\StringUtil::specialchars($varValue['openended_width']);
        }
        if (\strlen((string) $varValue['openended_maxlen'])) {
            $this->arrAttributes['maxlength'] = \Contao\StringUtil::specialchars($varValue['openended_maxlen']);
        }
        if (\strlen((string) $varValue['openended_textinside'])) {
            $this->arrAttributes['value'] = \Contao\StringUtil::specialchars($varValue['openended_textinside']);
        }
        if (\strlen((string) $this->varValue)) {
            $this->arrAttributes['value'] = \Contao\StringUtil::specialchars($this->varValue);
        }
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
        if (\strlen((string) $varValue['openended_rows'])) {
            $this->arrAttributes['rows'] = \Contao\StringUtil::specialchars($varValue['openended_rows']);
        }
        if (\strlen((string) $varValue['openended_cols'])) {
            $this->arrAttributes['cols'] = \Contao\StringUtil::specialchars($varValue['openended_cols']);
        }
        if (!\strlen((string) $this->varValue)) {
            if (\strlen((string) $varValue['openended_textinside'])) {
                $this->varValue = $varValue['openended_textinside'];
            }
        }
    }

    /**
     * Trim values.
     *
     * @param mixed
     * @param mixed $varInput
     *
     * @return mixed
     */
    protected function validator($varInput)
    {
        $oldlabel = $this->label;
        $label = (\strlen((string) $this->label)) ? $this->label : $this->title;
        $this->label = $label;
        if (\is_array($varInput)) {
            $result = parent::validator($varInput);
        } else {
            $result = parent::validator(trim((string) $varInput));
            $result = $this->check_bounds($result);
        }
        $this->label = $oldlabel;

        return $result;
    }

    /**
     * Validates certain fields against lower/upper bounds.
     *
     * @param string
     * @param mixed $varInput
     *
     * @return string
     */
    protected function check_bounds($varInput)
    {
        if ($this->hasErrors() || !\strlen((string) $varInput)) {
            // Don't check any further, value might not be a valid string to be compared against bounds
            return $varInput;
        }

        $result = $varInput;

        if (\strlen((string) $this->strLowerBound)) {
            $strErrMsg = $GLOBALS['TL_LANG']['ERR']['lower_bound'];
            $lower = (int) ($this->strLowerBound);

            switch ($this->questiontype) {
                case 'oe_integer':
                    $value = (int) $varInput;
                    if ($value < $lower) {
                        $this->addError(sprintf($strErrMsg, $value, $this->label, $lower));
                    }
                    break;

                case 'oe_float':
                    $lower = (float) ($this->strLowerBound);
                    $value = (float) $varInput;
                    if ($value < $lower) {
                        $this->addError(sprintf($strErrMsg, $value, $this->label, $lower));
                    }
                    break;

                case 'oe_date':
                    // $varInput is a string like '25.12.2009', use the Date class to get the comparable timestamp.
                    // This is not well documented in .../Date.php
                    $objDateValue = new \Contao\Date($varInput, $GLOBALS['TL_CONFIG']['dateFormat']);
                    $value = $objDateValue->timestamp;
                    if ($value < $lower) {
                        $objDateLower = new \Contao\Date($lower, $GLOBALS['TL_CONFIG']['dateFormat']);
                        $this->addError(sprintf($strErrMsg, $objDateValue->date, $this->label, $objDateLower->date));
                    }
                    // correct valid inputs like 31.11.2009 to 01.12.2009
                    $result = $objDateValue->date;
                    break;

                case 'oe_time':
                    $objDateValue = new \Contao\Date($varInput, $GLOBALS['TL_CONFIG']['timeFormat']);
                    $value = $objDateValue->timestamp;
                    if ($value < $lower) {
                        $objDateLower = new \Contao\Date($lower, $GLOBALS['TL_CONFIG']['timeFormat']);
                        $this->addError(sprintf($strErrMsg, $objDateValue->time, $this->label, $objDateLower->time));
                    }
                    // correct valid inputs like 13:59:xyz etc to 13:59
                    $result = $objDateValue->time;
                    break;
            }
        }

        if (\strlen((string) $this->strUpperBound)) {
            $strErrMsg = $GLOBALS['TL_LANG']['ERR']['upper_bound'];
            $upper = (int) ($this->strUpperBound);

            switch ($this->questiontype) {
                case 'oe_integer':
                    $value = (int) $varInput;
                    if ($value > $upper) {
                        $this->addError(sprintf($strErrMsg, $value, $this->label, $upper));
                    }
                    break;

                case 'oe_float':
                    $upper = (float) ($this->strUpperBound);
                    $value = (float) $varInput;
                    if ($value > $upper) {
                        $this->addError(sprintf($strErrMsg, $value, $this->label, $upper));
                    }
                    break;

                case 'oe_date':
                    $objDateValue = new \Contao\Date($varInput, $GLOBALS['TL_CONFIG']['dateFormat']);
                    $value = $objDateValue->timestamp;
                    if ($value > $upper) {
                        $objDateUpper = new \Contao\Date($upper, $GLOBALS['TL_CONFIG']['dateFormat']);
                        $this->addError(sprintf($strErrMsg, $objDateValue->date, $this->label, $objDateUpper->date));
                    }
                    // correct valid inputs like 31.11.2009 to 01.12.2009
                    $result = $objDateValue->date;
                    break;

                case 'oe_time':
                    $objDateValue = new \Contao\Date($varInput, $GLOBALS['TL_CONFIG']['timeFormat']);
                    $value = $objDateValue->timestamp;
                    if ($value > $upper) {
                        $objDateUpper = new \Contao\Date($upper, $GLOBALS['TL_CONFIG']['timeFormat']);
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
