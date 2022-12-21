<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */

namespace Hschottm\SurveyBundle;

use Contao\FrontendTemplate;
use Contao\StringUtil;

/**
 * Class FormMatrixQuestion.
 *
 * Form field "matrix question".
 *
 * @copyright  Helmut Schottmüller 2008-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class FormMatrixQuestion extends FormQuestionWidget
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'form_matrix';
    protected $arrRows = [];
    protected $arrColumns = [];
    protected $blnNeutralColumn = false;
    protected $strNeutralColumn = '';
    protected $blnBipolar = false;
    protected $strAdjective1 = '';
    protected $strAdjective2 = '';
    protected $strBipolarPosition = 'top';

    /**
     * Add specific attributes.
     *
     * @param string
     * @param mixed
     * @param mixed $strKey
     * @param mixed $varValue
     */
    public function __set($strKey, $varValue): void
    {
        switch ($strKey) {
            case 'surveydata':
                parent::__set($strKey, $varValue);
                $this->strClass = 'matrix'.((\strlen($varValue['cssClass']) ? ' '.$varValue['cssClass'] : ''));
                $this->arrRows = deserialize($varValue['matrixrows']);

                if (!\is_array($this->arrRows)) {
                    $this->arrRows = [];
                }
                $this->arrColumns = deserialize($varValue['matrixcolumns']);

                if (!\is_array($this->arrColumns)) {
                    $this->arrColumns = [];
                }
                $this->questiontype = $varValue['matrix_subtype'];
                $this->blnNeutralColumn = $varValue['addneutralcolumn'] ? true : false;
                $this->blnBipolar = $varValue['addbipolar'] ? true : false;
                $this->strNeutralColumn = $varValue['neutralcolumn'];
                $this->strAdjective1 = $varValue['adjective1'];
                $this->strAdjective2 = $varValue['adjective2'];
                $this->strBipolarPosition = $varValue['bipolarposition'];
                break;

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
        switch ($strKey) {
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
     * Validate input and set value.
     */
    public function validate(): void
    {
        $submit = $this->getPost('question');
        $value = [];
        $value = $submit[$this->id];
        $varInput = $this->validator($value);
        $this->value = $varInput;
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        $col_classes = [];
        $columncounter = 1;

        foreach ($this->arrColumns as $column) {
            $col_classes[$columncounter] = substr(standardize($column), 0, 28);
            ++$columncounter;
        }

        if ($this->blnBipolar) {
            $col_classes['leftadjective'] = substr(standardize($this->strAdjective1), 0, 28);
            $col_classes['rightadjective'] = substr(standardize($this->strAdjective2), 0, 28);
        }

        if ($this->blnNeutralColumn) {
            $col_classes['neutral'] = substr(standardize($this->strNeutralColumn), 0, 28);
        }
        $template = new FrontendTemplate('survey_question_matrix');
        $template->nrOfColumns = max(1, \count($this->arrColumns)) + ($this->blnNeutralColumn ? 1 : 0) + ($this->blnBipolar && 0 === strcmp($this->strBipolarPosition, 'aside') ? 2 : 0);
        $template->columns = $this->arrColumns;
        $template->col_classes = $col_classes;
        $template->rows = $this->arrRows;
        $template->rowWidth = '40%';
        $template->colWidth = floor(60.0 / ($template->nrOfColumns * 1.0)).'%';
        $template->bipolar = $this->blnBipolar;
        $template->bipolarTop = 0 === strcmp($this->strBipolarPosition, 'top');
        $template->bipolarAside = 0 === strcmp($this->strBipolarPosition, 'aside');
        $template->leftadjective = StringUtil::specialchars($this->strAdjective1);
        $template->rightadjective = StringUtil::specialchars($this->strAdjective2);
        $template->hasNeutralColumn = $this->blnNeutralColumn;
        $template->neutralColumn = StringUtil::specialchars($this->strNeutralColumn);
        $template->singleResponse = 0 === strcmp($this->questiontype, 'matrix_singleresponse');
        $template->multipleResponse = !$template->singleResponse;
        $template->ctrl_name = StringUtil::specialchars($this->strName);
        $template->ctrl_id = StringUtil::specialchars($this->strId);
        $template->ctrl_class = (\strlen($this->strClass) ? ' '.$this->strClass : '');
        $template->values = $this->varValue;
        $widget = $template->parse();
        $widget .= $this->addSubmit();

        return $widget;
    }

    /**
     * Create a string representation of the question result.
     *
     * @return string
     */
    public function getResultStringRepresentation()
    {
        $result = '';

        $rowcounter = 1;

        foreach ($this->arrRows as $row) {
            $choices = [];
            $columncounter = 1;
            $foundvalues = \is_array($this->varValue[$rowcounter]) ? $this->varValue[$rowcounter] : [];

            foreach ($this->arrColumns as $column) {
                if (0 === strcmp($this->questiontype, 'matrix_singleresponse')) {
                    if ($this->varValue[$rowcounter] === $columncounter) {
                        array_push($choices, $column);
                    }
                } else {
                    if (\in_array($columncounter, $foundvalues, true)) {
                        array_push($choices, $column);
                    }
                }
                ++$columncounter;
            }

            if ($this->blnNeutralColumn) {
                if (0 === strcmp($this->questiontype, 'matrix_singleresponse')) {
                    if ($this->varValue[$rowcounter] === $columncounter) {
                        array_push($choices, $this->strNeutralColumn);
                    } else {
                        if (\in_array($columncounter, $foundvalues, true)) {
                            array_push($choices, $this->strNeutralColumn);
                        }
                    }
                }
            }

            if (\count($choices)) {
                $result .= $row.': '.implode(', ', $choices)."\n";
            }
            ++$rowcounter;
        }

        return $result;
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
        if (0 === strcmp($this->questiontype, 'matrix_singleresponse') && $this->mandatory && !\is_array($varInput)) {
            $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_matrix'], $this->title));

            return $varInput;
        }

        if ((!$varInput || \count($varInput) !== \count($this->arrRows)) && $this->mandatory) {
            $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_matrix'], $this->title));

            return $varInput;
        }

        return $varInput;
    }
}
