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

use Contao\Widget;

/**
 * Class FormQuestionWidget.
 *
 * Base class for survey question widgets
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class FormQuestionWidget extends Widget
{
    /**
     * Submit user input.
     *
     * @var bool
     */
    protected $blnSubmitInput = true;

    /**
     * Template.
     *
     * @var string
     */
    protected $questionNumber = 0;
    protected $pageQuestionNumber = 0;
    protected $pageNumber = 0;
    protected $absoluteNumber = 0;
    protected $question = '';
    protected $title = '';
    protected $help = '';
    protected $questiontype = '';
    protected $hidetitle;

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
                $this->arrConfiguration['mandatory'] = $varValue['obligatory'] ? true : false;
                $this->strId = $varValue['id'];
                $this->strName = 'question['.$varValue['id'].']';
                $this->question = $varValue['question'];
                $this->title = $varValue['title'];
                $this->help = $varValue['help'];
                $this->hidetitle = $varValue['hidetitle'];
                break;

            case 'mandatory':
                $this->arrConfiguration['mandatory'] = $varValue ? true : false;
                break;

            case 'pageNumber':
                $this->pageNumber = $varValue;
                break;

            case 'absoluteNumber':
                $this->absoluteNumber = $varValue;
                break;

            case 'questionNumber':
                $this->questionNumber = $varValue;
                break;

            case 'pageQuestionNumber':
                $this->pageQuestionNumber = $varValue;
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
            case 'question':
                return $this->question;
                break;

            case 'title':
                return $this->title;
                break;

            case 'questionNumber':
                return $this->questionNumber;
                break;

            case 'pageQuestionNumber':
                return $this->pageQuestionNumber;
                break;

            case 'pageNumber':
                return $this->pageNumber;
                break;

            case 'absoluteNumber':
                return $this->absoluteNumber;
                break;

            case 'showTitle':
                return !$this->hidetitle;
                break;

            case 'help':
                return $this->help;
                break;

            case 'empty':
                return !\is_array($this->varValue) && !\strlen($this->varValue) ? true : false;
                break;
        }

        return parent::__get($strKey);
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        // overwrite in parent classes
    }

    public function hasLabel()
    {
        if ('' === $this->title || $this->showTitle) {
            return false;
        }

        return true;
    }

    /**
     * Generate the label and return it as string.
     *
     * @return string The label markup
     */
    public function generateLabel()
    {
        if (!$this->hasLabel()) {
            return '';
        }

        return sprintf(
            '<label%s%s>%s%s%s</label>',
            (\strlen($this->strId) ? ' for="ctrl_'.$this->strId.'"' : ''),
            ('' !== $this->strClass ? ' class="'.$this->strClass.'"' : ''),
            ($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].' </span>' : ''),
            $this->title,
            ($this->mandatory ? '<span class="mandatory">*</span>' : '')
        );
    }

    /**
     * Create a string representation of the question result.
     *
     * @return string
     */
    public function getResultStringRepresentation()
    {
        $result = '';

        if (!\is_array($this->varValue) && \strlen($this->varValue)) {
            $result .= $this->varValue."\n";
        }

        return $result;
    }
}
