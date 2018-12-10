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
 * Class SurveyQuestionConstantsum.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionConstantsum extends SurveyQuestion
{
    protected $choices = [];

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

    public function getAnswersAsHTML()
    {
        if (\is_array($this->statistics['cumulated'])) {
            $template = new \FrontendTemplate('survey_answers_constantsum');
            $template->choices = deserialize($this->arrData['sumchoices'], true);
            $template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
            $template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
            $template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];
            $template->cumulated = $this->statistics['cumulated'];

            return $template->parse();
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

        if (\is_array($this->statistics['cumulated'])) {
            $arrChoices = deserialize($this->arrData['sumchoices'], true);
            $counter = 1;
            foreach ($arrChoices as $id => $choice) {
                $exporter->setCellValue($sheet, $row + $counter - 1, 1, [ExcelExporter::DATA => $choice]);
                $counter += 2;
            }
            $counter = 1;
            $idx = 1;
            foreach ($arrChoices as $id => $choice) {
                $acounter = 2;
                foreach ($this->statistics['cumulated'][$idx] as $answervalue => $nrOfAnswers) {
                    $exporter->setCellValue($sheet, $row + $counter - 1, $acounter, [ExcelExporter::DATA => $answervalue, ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
                    $exporter->setCellValue($sheet, $row + $counter, $acounter, [ExcelExporter::DATA => (($nrOfAnswers) ? $nrOfAnswers : 0), ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
                    ++$acounter;
                }
                ++$idx;
                $counter += 2;
            }

            $row += \count($arrChoices) * 2 + 1;
        }
    }

    /**
     * Exports constant sum question headers and all existing answers.
     *
     * Constant sum questions occupy one column for every choice / input field.
     * All choices are exported turned ccw in the header.
     * The answer values are given formatted numerically.
     * Common question headers, e.g. the id, question-numbers, title are exported in merged cells
     * spanning all subquestion columns.
     *
     * As a side effect the width for each column is calculated and set via the given $xls object.
     * Row height is currently calculated/set ONLY for the row with subquestions, which is turned
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
     *
     * @TODO: eventually give out just indexes instead of choice strings to save width? Then the possible coices must be shown in the header.
     */
    public function exportDetailsToExcel(&$exporter, $sheet, &$row, &$col, $questionNumbers, $participants)
    {
        /*
        print "<pre>\n";
        var_export(deserialize($this->arrData['sumchoices'], true));
        foreach ($this->statistics['participants'] as $k => $v) {
            print "'$k' => ";
            var_export(deserialize($v[0]['result']));
            print "\n";
        }
        var_export($this->statistics);
        var_export($this->arrData);
        print "</pre>\n";
        die();
        */
        $valueCol = $col;
        $rotateInfo = [];
        $headerCells = $this->exportQuestionHeadersToExcel($exporter, $sheet, $row, $col, $questionNumbers, $rotateInfo);
        $resultCells = $this->exportDetailResults($exporter, $sheet, $row, $valueCol, $participants);
        /*
        foreach ($rotateInfo as $intRow => $arrText) {
            foreach ($arrText as $intCol => $strText) {
                $this->setRowHeightForRotatedText($xls, $sheet, $intRow, $intCol, $strText);
            }
        }
*/
        return array_merge($headerCells, $resultCells);
    }

    protected function calculateStatistics()
    {
        if (array_key_exists('id', $this->arrData) && array_key_exists('parentID', $this->arrData)) {
            $objResult = \Database::getInstance()->prepare('SELECT * FROM tl_survey_result WHERE qid=? AND pid=?')
                ->execute($this->arrData['id'], $this->arrData['parentID']);
            if ($objResult->numRows) {
                $this->calculateAnsweredSkipped($objResult);
                $this->calculateCumulated();
            }
        }
    }

    protected function calculateCumulated()
    {
        $cumulated = [];
        $cumulated['other'] = [];
        foreach ($this->arrStatistics['answers'] as $answer) {
            $arrAnswer = deserialize($answer, true);
            if (\is_array($arrAnswer)) {
                foreach ($arrAnswer as $answerkey => $answervalue) {
                    ++$cumulated[$answerkey][$answervalue];
                }
            }
        }
        foreach ($cumulated as $key => $value) {
            ksort($value);
            $cumulated[$key] = $value;
        }
        $this->arrStatistics['cumulated'] = $cumulated;
    }

    /**
     * Exports the column headers for a question of type 'matrix'.
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
    protected function exportQuestionHeadersToExcel(&$exporter, $sheet, &$row, &$col, $questionNumbers, &$rotateInfo)
    {
        $this->choices = deserialize($this->arrData['sumchoices'], true);
        foreach ($this->choices as $k => $v) {
            $this->choices[$k] = \String::decodeEntities($v);
        }
        $numcols = \count($this->choices);
        $result = [];
        // ID and question numbers
        $data = [
          ExcelExporter::DATA => $this->id,
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        $data = [
          ExcelExporter::DATA => $questionNumbers['abs_question_no'],
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT,
          ExcelExporter::FONTSTYLE => ExcelExporter::FONTSTYLE_ITALIC
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        $data = [
          ExcelExporter::DATA => $questionNumbers['page_no'].'.'.$questionNumbers['rel_question_no'],
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT,
          ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD,
          ExcelExporter::ALIGNMENT => ExcelExporter::ALIGNMENT_H_CENTER
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        // question type
        $data = [
          ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        // answered and skipped info, retrieves all answers as a side effect
        $data = [
          ExcelExporter::DATA => $this->statistics['answered'],
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        $data = [
          ExcelExporter::DATA => $this->statistics['skipped'],
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        // question title
        $data = [
          ExcelExporter::DATA => \String::decodeEntities($this->title)).($this->arrData['obligatory'] ? ' *' : '',
          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_STRING,
          ExcelExporter::ALIGNMENT => ExcelExporter::ALIGNMENT_H_CENTER,
          ExcelExporter::TEXTWRAP => true
        ];
        if ($numcols > 1)
        {
          $data[ExcelExporter::MERGE] = $this->getCell($row, $col) . ":" . $this->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        if (1 === $numcols) {
            // This is a strange case: a constant sum question with just one choice.
            // However, users do that (at least for testing) and have the right to do so.
            // Just add the one and only choice, without rotation ...
            $data = [
              ExcelExporter::DATA => $this->choices[0],
              ExcelExporter::ALIGNMENT => ExcelExporter::ALIGNMENT_H_CENTER,
              ExcelExporter::TEXTWRAP => true,
              ExcelExporter::BORDERBOTTOM => ExcelExporter::BORDER_THIN,
              ExcelExporter::BORDERBOTTOMCOLOR => '#000000',
            ];
            $exporter->setCellValue($sheet, $row, $col, $data);
            ++$col;
        } else {
            // output all choice columns
            $rotateInfo[$row] = [];
            $narrowWidth = 2 * 640;
            $sumWidth = 0;
            foreach ($this->choices as $key => $choice) {
              $data = [
                ExcelExporter::DATA => $choice,
                ExcelExporter::ALIGNMENT => ExcelExporter::ALIGNMENT_H_CENTER,
                ExcelExporter::TEXTWRAP => true,
                ExcelExporter::TEXTROTATE => ExcelExporter::TEXTROTATE_COUNTERCLOCKWISE,
                ExcelExporter::BORDERBOTTOM => ExcelExporter::BORDER_THIN,
                ExcelExporter::BORDERBOTTOMCOLOR => '#000000',
              ];
              $exporter->setCellValue($sheet, $row, $col, $data);
              ++$col;
            }
        }
        ++$row;

        return $result;
    }

    /**
     * Exports all results/answers to the question at hand.
     *
     * Sets some column widthes as a side effect.
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
    protected function exportDetailResults(&$exporter, $sheet, &$row, &$col, $participants)
    {
        $cells = [];
        $startCol = $col;
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
                $col = $startCol;
                $arrAnswers = deserialize($data, true);
                foreach ($this->choices as $k => $choice) {
                    $strAnswer = '';
                    if (array_key_exists($k + 1, $arrAnswers)) {
                        $strAnswer = $arrAnswers[$k + 1];
                    }
                    if (\strlen($strAnswer)) {
                        // Set value to numeric, when the coices are e.g. school grades '1'-'5', a common case (for me).
                        // Then the user is able to work with formulars in Excel/Calc, avarage for instance.
                        $exporter->setCellValue($sheet, $row, $col, [
                          ExcelExporter::DATA => $strAnswer,
                          ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT,
                          ExcelExporter::ALIGNMENT => ExcelExporter::ALIGNMENT_H_CENTER,
                          ExcelExporter::TEXTWRAP => true
                        ]);
                        // Guess a minimum column width for the answer column.
                        /*
                        $minColWidth = max(
                            ($this->getLongestWordLen($strAnswer) + 3) * 256,
                            $xls->getcolwidth($sheet, $col),
                            min(\strlen($strAnswer) / 8 * 256, 40 * 256)
                        );
                        $xls->setcolwidth($sheet, $col, $minColWidth);
                        */
                    }
                    ++$col;
                }
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
     * @param string $text  the text to consider in calculation
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
