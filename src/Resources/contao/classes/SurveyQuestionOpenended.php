<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Hschottm\SurveyBundle\Export\Exporter;

/**
 * Class SurveyQuestionOpenended.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionOpenended extends SurveyQuestion
{
    /**
     * Import String library.
     *
     * @param mixed $question_id
     */
    public function __construct($question_id = 0)
    {
        parent::__construct($question_id);
    }

    public function __set($name, $value)
    {
        switch ($name) {
            default:
                parent::__set($name, $value);
                break;
        }
    }

    public function exportDataToExcel(&$exporter, $sheet, &$row)
    {
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => 'ID', Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD, Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => $this->id, Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT, Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['title'][0], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => $this->title]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['question'][0], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => strip_tags($this->question)]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['answered'], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => $this->statistics['answered'], Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['skipped'], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [Exporter::DATA => $this->statistics['skipped'], Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT]);
        ++$row;

        $exporter->setCellValue($sheet, $row, 0, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['answers'], Exporter::BGCOLOR => $this->titlebgcolor, Exporter::COLOR => $this->titlecolor, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);

        $col = 2;
        if (\is_array($this->statistics['answers'])) {
            foreach ($this->statistics['answers'] as $answer) {
                $exporter->setCellValue($sheet, $row, $col++, [Exporter::DATA => $answer]);
            }
        }
        $row += 2;
    }

    /**
     * Exports question headers and all existing answers.
     *
     * As a side effect the width for each column is calculated and set via the given $exporter object.
     * Row height is currently calculated/set ONLY for the row with subquestions/choices (neccessary
     * for matrix questions etc, here only test strings are given out for the testing), which is turned
     * 90° ccw ... thus it is effectively also a text width calculation.
     *
     * Not setting row(/text) height explicitly in the general case is no problem in OpenOffice Calc 3.1,
     * which does a good job here by default. However Excel 95/97 seems to do it worse,
     * I can't test that currently. "Set optimal row height" might help users of Excel.
     *
     * @param object &$exporter       instance of the Excel exporter object
     * @param string $sheet           name of the worksheet
     * @param int    &$row            row to put a cell in
     * @param int    &$col            col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  $participants    array with all participant data
     *
     * @return array the cells to be added to the export
     */
    public function exportDetailsToExcel(&$exporter, $sheet, &$row, &$col, $questionNumbers, $participants)
    {
        $rotateInfo = [];
        $headerCells = $this->exportQuestionHeadersToExcel($exporter, $sheet, $row, $col, $questionNumbers, $rotateInfo);
        $resultCells = $this->exportDetailResults($exporter, $sheet, $row, $col, $participants);
        ++$col;
        return array_merge($headerCells, $resultCells);
    }

    protected function calculateStatistics()
    {
        if (array_key_exists('id', $this->arrData) && array_key_exists('parentID', $this->arrData)) {
            $objResult = \Database::getInstance()->prepare('SELECT * FROM tl_survey_result WHERE qid=? AND pid=?')
                ->execute($this->arrData['id'], $this->arrData['parentID']);
            if ($objResult->numRows) {
                $this->calculateAnsweredSkipped($objResult);
            }
        }
    }

    /**
     * Exports the column headers for a question of type 'openended' and currently has some test code.
     *
     * Several rows are returned, so that the user of the Excel file is able to
     * use them for reference, filtering and sorting.
     *
     * @param object &$exporter       instance of the Excel exporter object
     * @param string $sheet           name of the worksheet
     * @param int    &$row            in/out row to put a cell in
     * @param int    &$col            in/out col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  &$rotateInfo     out param with row => text for later calculation of row height
     *
     * @return array the cells to be added to the export
     */
    protected function exportQuestionHeadersToExcel(&$exporter, $sheet, &$row, &$col, $questionNumbers, &$rotateInfo)
    {
        $result = [];

        // ID and question numbers
        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $this->id,
          Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $questionNumbers['abs_question_no'],
          Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
          Exporter::FONTSTYLE => Exporter::FONTSTYLE_ITALIC
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $questionNumbers['page_no'].'.'.$questionNumbers['rel_question_no'],
          Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
          Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
          Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER
        ]);

        // question type
        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]
        ]);

        // answered and skipped info, retrieves all answers as a side effect
        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $this->statistics['answered'],
          Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => $this->statistics['skipped'],
          Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT
        ]);

        // question title
        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => \StringUtil::decodeEntities($this->title).($this->arrData['obligatory'] ? ' *' : ''),
          Exporter::CELLTYPE => Exporter::CELLTYPE_STRING,
          Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
          Exporter::TEXTWRAP => true
        ]);

        // empty cell used in other question types, for the formatting
        $exporter->setCellValue($sheet, $row++, $col, [
          Exporter::DATA => '',
          Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
          Exporter::TEXTWRAP => true,
          Exporter::TEXTROTATE => ($this->arrData['addother'] && ($key == \count($this->choices) - 1)) ? Exporter::TEXTROTATE_NONE : Exporter::TEXTROTATE_COUNTERCLOCKWISE,
          Exporter::BORDERBOTTOM => Exporter::BORDER_THIN,
          Exporter::BORDERBOTTOMCOLOR => '#000000',
        ]);

        return $result;
    }

    /**
     * Exports all results/answers to the question at hand.
     *
     * Sets column widthes as a side effect.
     *
     * @param object &$exporter    instance of the Excel exporter object
     * @param string $sheet        name of the worksheet
     * @param int    &$row         row to put a cell in
     * @param int    &$col         col to put a cell in
     * @param array  $participants array with all participant data
     *
     * @return array the cells to be added to the export
     *
     * @TODO: make alignment and max colwidth configurable in dcaconfig.php ?
     */
    protected function exportDetailResults(&$exporter, $sheet, &$row, &$col, $participants)
    {
        $cells = [];
        foreach ($participants as $key => $value) {
            $data = false;
            if (\strlen($this->statistics['participants'][$key]['result'])) {
                // future state of survey_ce
                $data = $this->statistics['participants'][$key]['result'];
            } elseif (\strlen($this->statistics['participants'][$key][0]['result'])) {
                // current state of survey_ce: additional subarray with always 1 entry
                $data = $this->statistics['participants'][$key][0]['result'];
            }
            if ($data) {
                $data = \StringUtil::decodeEntities($data);
                $exporter->setCellValue($sheet, $row, $col, [
                  Exporter::DATA => $data,
                  Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                  Exporter::TEXTWRAP => true
                ]);
            }
            ++$row;
        }

        return $cells;
    }

    public function resultAsString($res)
  	{
  		return $res;
  	}
}
