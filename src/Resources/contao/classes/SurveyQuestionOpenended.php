<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Hschottm\SurveyBundle\Export\ExcelExporter;

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
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => 'ID', ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => $this->id, ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['title'][0], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => $this->title]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['question'][0], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => strip_tags($this->question)]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['answered'], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => $this->statistics['answered'], ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
        ++$row;
        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['skipped'], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);
        $exporter->setCellValue($sheet, $row, 1, [ExcelExporter::DATA => $this->statistics['skipped'], ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
        ++$row;

        $exporter->setCellValue($sheet, $row, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['answers'], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);

        $col = 1;
        if (\is_array($this->statistics['answers'])) {
            foreach ($this->statistics['answers'] as $answer) {
                $exporter->setCellValue($sheet, $row, $col++, [ExcelExporter::DATA => $answer]);
            }
        }
        $row += 2;
    }

    /**
     * Exports question headers and all existing answers.
     *
     * As a side effect the width for each column is calculated and set via the given $xls object.
     * Row height is currently calculated/set ONLY for the row with subquestions/choices (neccessary
     * for matrix questions etc, here only test strings are given out for the testing), which is turned
     * 90° ccw ... thus it is effectively also a text width calculation.
     *
     * Not setting row(/text) height explicitly in the general case is no problem in OpenOffice Calc 3.1,
     * which does a good job here by default. However Excel 95/97 seems to do it worse,
     * I can't test that currently. "Set optimal row height" might help users of Excel.
     *
     * @param object &$xls            the excel object to call methods on
     * @param string $sheet           name of the worksheet
     * @param int    &$row            row to put a cell in
     * @param int    &$col            col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  $participants    array with all participant data
     *
     * @return array the cells to be added to the export
     */
    public function exportDetailsToExcel(&$xls, $sheet, &$row, &$col, $questionNumbers, $participants)
    {
        $rotateInfo = [];
        $headerCells = $this->exportQuestionHeadersToExcel($xls, $sheet, $row, $col, $questionNumbers, $rotateInfo);
        $resultCells = $this->exportDetailResults($xls, $sheet, $row, $col, $participants);

        foreach ($rotateInfo as $intRow => $arrText) {
            foreach ($arrText as $intCol => $strText) {
                $this->setRowHeightForRotatedText($xls, $sheet, $intRow, $intCol, $strText);
            }
        }

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
     * @param object &$xls            the excel object to call methods on
     * @param string $sheet           name of the worksheet
     * @param int    &$row            in/out row to put a cell in
     * @param int    &$col            in/out col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  &$rotateInfo     out param with row => text for later calculation of row height
     *
     * @return array the cells to be added to the export
     */
    protected function exportQuestionHeadersToExcel(&$xls, $sheet, &$row, &$col, $questionNumbers, &$rotateInfo)
    {
        $result = [];

        // ID and question numbers
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'data' => $this->id, 'type' => CELL_FLOAT,
        ];
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'fontstyle' => XLSFONT_STYLE_ITALIC,
            'data' => $questionNumbers['abs_question_no'], 'type' => CELL_FLOAT,
        ];
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_CENTER,
            'data' => $questionNumbers['page_no'].'.'.$questionNumbers['rel_question_no'],
        ];

        // question type
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]),
        ];

        // answered and skipped info, retrieves all answers as a side effect
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'data' => $this->statistics['answered'], 'type' => CELL_FLOAT,
        ];
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'data' => $this->statistics['skipped'], 'type' => CELL_FLOAT,
        ];

        // question title
        $title = utf8_decode(StringUtil::decodeEntities($this->title)).($this->arrData['obligatory'] ? ' *' : '');
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
            'data' => $title,
        ];
        // Guess a minimum column width for the title.
        $minColWidth = max(
            ($this->getLongestWordLen($title) + 3) * 256,
            $xls->getcolwidth($sheet, $col)
        );
        $xls->setcolwidth($sheet, $col, $minColWidth);

        // empty cell used in other question types, for the formatting
        $result[] = [
            'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
            'textrotate' => XLSXF_TEXTROTATION_COUNTERCLOCKWISE,
            'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
            'borderbottom' => XLSXF_BORDER_THIN, 'borderbottomcolor' => '#000000',
            'data' => '',
        ];

        return $result;
    }

    /**
     * Exports all results/answers to the question at hand.
     *
     * Sets column widthes as a side effect.
     *
     * @param object &$xls         the excel object to call methods on
     * @param string $sheet        name of the worksheet
     * @param int    &$row         row to put a cell in
     * @param int    &$col         col to put a cell in
     * @param array  $participants array with all participant data
     *
     * @return array the cells to be added to the export
     *
     * @TODO: make alignment and max colwidth configurable in dcaconfig.php ?
     */
    protected function exportDetailResults(&$xls, $sheet, &$row, &$col, $participants)
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
                $data = utf8_decode(StringUtil::decodeEntities($data));
                $cells[] = [
                    'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                    'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                    'data' => $data,
                ];
                // Guess a minimum column width.
                $minColWidth = max(
                    ($this->getLongestWordLen($data) + 3) * 256,
                    $xls->getcolwidth($sheet, $col),
                    min(\strlen($data) / 8 * 256, 40 * 256)
                );
                $xls->setcolwidth($sheet, $col, $minColWidth);
            }
            ++$row;
        }

        return $cells;
    }

    /**
     * Guesses and sets a height for the given row containing rotatet text (90° cw or ccw).
     *
     * The guess assumes the default font and is based on the existing col width.
     *
     * @param object &$xls  the excel object to call methods on
     * @param string $sheet name of the worksheet
     * @param int    $row   row to calculate/set the height for
     * @param int    $col   col to consider in the calculation (it's current width)
     * @param string $text  the text in the cell
     *
     * @TODO: refactor out into superclass SurveyQuestion
     * @TODO: define constants or dcaconfig.php settings for the hardcoded values
     */
    protected function setRowHeightForRotatedText(&$xls, $sheet, $row, $col, $text)
    {
        // 1 line of rotated text needs ~ 640 colwidth units.
        $hscale = 110;
        $minRowHeight = max(
            ($this->getLongestWordLen($text) + 3) * $hscale,
            (int) ((utf8_strlen($text) + 3) * $hscale / round($xls->getcolwidth($sheet, $col) / 640)),
            $xls->getrowheight($sheet, $row)
        );
        $xls->setrowheight($sheet, $row, $minRowHeight);
    }

    /**
     * Returns the length of the longest word in the given string.
     *
     * @param: string $strString  the input string to process
     * @return: int  the lenght of the longest word
     *
     * @TODO: refactor out into superclass SurveyQuestion
     * @TODO: make chars to split on configurable via dcaconfig.php ?
     *
     * @param mixed $strString
     */
    protected function getLongestWordLen($strString)
    {
        $result = 0;
        $strString = strip_tags($strString);
        // split on some typical punktion chars too, even though Excel/Calc does not break lines
        // on these, else e.g. a comma separated list (without spaces) would be considered as a
        // very long line and lead to a much too wide column.
        $strString = preg_replace('/[-,;:!|\.\?\t\n\r\/\\\\]+/', ' ', $strString);
        $arrChunks = preg_split('/\s+/', $strString);
        foreach ($arrChunks as $strChunk) {
            $len = utf8_strlen($strChunk);
            if ($len > $result) {
                $result = $len;
            }
        }

        return $result;
    }
}
