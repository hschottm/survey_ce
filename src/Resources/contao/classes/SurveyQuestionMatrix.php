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

use Contao\Database;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Hschottm\SurveyBundle\Export\Exporter;

/**
 * Class SurveyQuestionMatrix.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionMatrix extends SurveyQuestion
{
    protected $subquestions = [];
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

    public function __set($name, $value): void
    {
        switch ($name) {
            default:
                parent::__set($name, $value);
                break;
        }
    }

    public function getResultData(): array
    {
        $result = [];

        if (\is_array($this->statistics['cumulated'])) {
            $result['statistics'] = $this->statistics;
            $result['choices'] = StringUtil::deserialize($this->arrData['matrixcolumns'], true);
            $result['rows'] = StringUtil::deserialize($this->arrData['matrixrows'], true);
        }

        return $result;
    }

    public function getAnswersAsHTML()
    {
        if (!empty($resultData = $this->getResultData())) {
            $template = new FrontendTemplate('survey_answers_matrix');
            $template->choices = $resultData['choices'];
            $template->rows = $resultData['rows'];
            $template->statistics = $resultData['statistics'];
            $template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
            $template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
            $template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];
            $template->cumulated = $resultData['statistics']['cumulated'];

            return $template->parse();
        }
    }

    public function exportDataToExcel(& $exporter, $sheet, & $row): void
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

        if (\is_array($this->statistics['cumulated'])) {
            $arrRows = deserialize($this->arrData['matrixrows'], true);
            $arrChoices = deserialize($this->arrData['matrixcolumns'], true);
            $row_counter = 1;

            foreach ($arrRows as $id => $rowdata) {
                $exporter->setCellValue($sheet, $row + $row_counter, $col, [Exporter::DATA => $rowdata, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
                ++$row_counter;
            }

            $row_counter = 1;

            foreach ($arrRows as $id => $rowdata) {
                $col_counter = 1;

                foreach ($arrChoices as $choiceid => $choice) {
                    if (1 === $row_counter) {
                        $exporter->setCellValue($sheet, $row, $col + $col_counter, [Exporter::DATA => $choice, Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD]);
                    }

                    $exporter->setCellValue($sheet, $row + $row_counter, $col + $col_counter, [Exporter::DATA => ($this->statistics['cumulated'][$row_counter][$col_counter] ?: 0), Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT]);
                    ++$col_counter;
                }
                ++$row_counter;
            }

            $row += \count($arrRows);
        }
        $row += 2;
    }

    /**
     * Exports matrix question headers and all existing answers.
     *
     * Matrix questions currently occupy one column for every matrix row / subquestion, which
     * is given out turned ccw in the header, regardless of the subtype single/multiple choice.
     * This is so to avoid excessive numbers of columns for the multiple choice subtype (num rows * num cols).
     * Instead the value cells carry the choice/s (matrix col names), either a single value
     * (single choice) or a delimiter '|' separated list of them (multiple choice).
     * Common question headers, e.g. the id, question-numbers, title are exported in merged cells
     * spanning all subquestion columns.
     *
     * As a side effect the width for each column is calculated and set via the given $exporter object.
     * Row height is currently calculated/set ONLY for the row with subquestions, which is turned
     * 90° ccw ... thus it is effectively also a text width calculation.
     *
     * Not setting row(/text) height explicitly in the general case is no problem in OpenOffice Calc 3.1,
     * which does a good job here by default. However Excel 95/97 seems to do it worse,
     * I can't test that currently. "Set optimal row height" might help users of Excel.
     *
     * @param object $exporter        instance of the Excel exporter object
     * @param string $sheet           name of the worksheet
     * @param int    $row             row to put a cell in
     * @param int    $col             col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  $participants    array with all participant data
     *
     * @return array the cells to be added to the export
     *
     * @TODO: eventually give out just indexes instead of choice strings to save width? Then the possible coices must be shown in the header.
     */
    public function exportDetailsToExcel(& $exporter, $sheet, & $row, & $col, $questionNumbers, $participants)
    {
        $valueCol = $col;
        $rotateInfo = [];
        $headerCells = $this->exportQuestionHeadersToExcel($exporter, $sheet, $row, $col, $questionNumbers, $rotateInfo);
        $resultCells = $this->exportDetailResults($exporter, $sheet, $row, $valueCol, $participants);

        return array_merge($headerCells, $resultCells);
    }

    public function resultAsString($res)
    {
        $arrAnswer = deserialize($res, true);

        if (\is_array($arrAnswer)) {
            // ToDo: fix the following workaround
            // $arrAnswer can also be a multidimensional array here, which then does not work
            return @implode(', ', $arrAnswer);
        }

        return '';
    }

    protected function calculateStatistics(): void
    {
        if (\array_key_exists('id', $this->arrData) && \array_key_exists('parentID', $this->arrData)) {
            $objResult = Database::getInstance()->prepare('SELECT * FROM tl_survey_result WHERE qid=? AND pid=?')
                ->execute($this->arrData['id'], $this->arrData['parentID'])
            ;

            if ($objResult->numRows) {
                $this->calculateAnsweredSkipped($objResult);
                $this->calculateCumulated();
            }
        }
    }

    protected function calculateCumulated(): void
    {
        $cumulated = [];
        $cumulated['other'] = [];

        foreach ($this->arrStatistics['answers'] as $answer) {
            $arrAnswer = deserialize($answer, true);

            if (\is_array($arrAnswer)) {
                foreach ($arrAnswer as $row => $answervalue) {
                    if (\is_array($answervalue)) {
                        foreach ($answervalue as $singleanswervalue) {
                            ++$cumulated[$row][$singleanswervalue];
                        }
                    } else {
                        // ToDo: fix this workaround
                        @++$cumulated[$row][$answervalue];
                    }
                }
            }
        }
        $this->arrStatistics['cumulated'] = $cumulated;
    }

    /**
     * Exports the column headers for a question of type 'matrix'.
     *
     * Several rows are returned, so that the user of the Excel file is able to
     * use them for reference, filtering and sorting.
     *
     * @param object $exporter        instance of the Excel exporter object
     * @param string $sheet           name of the worksheet
     * @param int    $row             in/out row to put a cell in
     * @param int    $col             in/out col to put a cell in
     * @param array  $questionNumbers array with page and question numbers
     * @param array  $rotateInfo      out param with row => text for later calculation of row height
     *
     * @return array the cells to be added to the export
     */
    protected function exportQuestionHeadersToExcel(& $exporter, $sheet, & $row, & $col, $questionNumbers, & $rotateInfo)
    {
        $this->subquestions = deserialize($this->arrData['matrixrows'], true);

        foreach ($this->subquestions as $k => $v) {
            $this->subquestions[$k] = StringUtil::decodeEntities($v);
        }
        $numcols = \count($this->subquestions);

        $this->choices = deserialize($this->arrData['matrixcolumns'], true);

        if ($this->arrData['addneutralcolumn']) {
            // TODO: i believe, the dash is better then the real text for the neutral column, make configurable?
            // $this->choices[] = $this->arrData['neutralcolumn'];
            $this->choices[] = '-';
        }

        foreach ($this->choices as $k => $v) {
            $this->choices[$k] = StringUtil::decodeEntities($v);
        }

        $result = [];

        // ID and question numbers
        $data = [
            Exporter::DATA => $this->id,
            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;
        $data = [
            Exporter::DATA => $questionNumbers['abs_question_no'],
            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
            Exporter::FONTSTYLE => Exporter::FONTSTYLE_ITALIC,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;
        $data = [
            Exporter::DATA => $questionNumbers['page_no'].'.'.$questionNumbers['rel_question_no'],
            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;

        // question type
        $data = [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype].', '.
                $GLOBALS['TL_LANG']['tl_survey_question'][$this->arrData['matrix_subtype']],
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);
        ++$row;

        // answered and skipped info, retrieves all answers as a side effect
        $data = [
            Exporter::DATA => $this->statistics['answered'],
            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;
        $data = [
            Exporter::DATA => $this->statistics['skipped'],
            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;

        // question title
        $data = [
            Exporter::DATA => StringUtil::decodeEntities($this->title).($this->arrData['obligatory'] ? ' *' : ''),
            Exporter::CELLTYPE => Exporter::CELLTYPE_STRING,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
            Exporter::TEXTWRAP => true,
        ];

        if ($numcols > 1) {
            $data[Exporter::MERGE] = $exporter->getCell($row, $col).':'.$exporter->getCell($row, $col + $numcols - 1);
        }
        $exporter->setCellValue($sheet, $row, $col, $data);

        ++$row;

        if (1 === $numcols) {
            // This is a strange case: a matrix question with just one subquestion.
            // However, users do that (at least for testing) and have the right to do so.
            // Just add the one and only subquestion, without rotation ...
            $data = [
                Exporter::DATA => $this->subquestions[0],
                Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                Exporter::TEXTWRAP => true,
                Exporter::BORDERBOTTOM => Exporter::BORDER_THIN,
                Exporter::BORDERBOTTOMCOLOR => '#000000',
            ];
            $exporter->setCellValue($sheet, $row, $col, $data);
            ++$col;
        } else {
            // output all subquestion columns
            $rotateInfo[$row] = [];
            $narrowWidth = 2 * 640;
            $sumWidth = 0;

            foreach ($this->subquestions as $key => $subquestion) {
                $data = [
                    Exporter::DATA => $subquestion,
                    Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                    Exporter::TEXTWRAP => true,
                    Exporter::TEXTROTATE => Exporter::TEXTROTATE_COUNTERCLOCKWISE,
                    Exporter::BORDERBOTTOM => Exporter::BORDER_THIN,
                    Exporter::BORDERBOTTOMCOLOR => '#000000',
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
     * @param object $exporter     instance of the Excel exporter object
     * @param string $sheet        name of the worksheet
     * @param int    $row          row to put a cell in
     * @param int    $col          col to put a cell in
     * @param array  $participants array with all participant data
     *
     * @return array the cells to be added to the export
     *
     * @TODO: make alignment and max colwidth configurable in dcaconfig.php ?
     */
    protected function exportDetailResults(& $exporter, $sheet, & $row, & $col, $participants)
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

                if ('matrix_singleresponse' === $this->arrData['matrix_subtype']) {
                    $emptyAnswer = false;

                    foreach ($this->subquestions as $k => $junk) {
                        $strAnswer = '';

                        if (\array_key_exists($k + 1, $arrAnswers)) {
                            $choice_key = $arrAnswers[$k + 1] - 1;

                            if (\array_key_exists($choice_key, $this->choices)) {
                                $strAnswer = $this->choices[$choice_key];
                            }
                        }

                        if (0 === \strlen($strAnswer)) {
                            $emptyAnswer = true;
                        }
                    }

                    foreach ($this->subquestions as $k => $junk) {
                        $strAnswer = '';

                        if (\array_key_exists($k + 1, $arrAnswers)) {
                            $choice_key = $arrAnswers[$k + 1] - 1;

                            if (\array_key_exists($choice_key, $this->choices)) {
                                $strAnswer = $this->choices[$choice_key];
                            }

                            if ($emptyAnswer) {
                                $strAnswer = ($k + 1).' - '.$strAnswer;
                            }
                        }

                        if (\strlen($strAnswer)) {
                            // Set value to numeric, when the coices are e.g. school grades '1'-'5', a common case (for me).
                            // Then the user is able to work with formulars in Excel/Calc, avarage for instance.
                            $exporter->setCellValue($sheet, $row, $col, [
                                Exporter::DATA => $strAnswer,
                                Exporter::CELLTYPE => is_numeric($strAnswer) ? Exporter::CELLTYPE_FLOAT : Exporter::CELLTYPE_STRING,
                                Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                                Exporter::TEXTWRAP => true,
                            ]);
                        }
                        ++$col;
                    }
                } elseif ('matrix_multipleresponse' === $this->arrData['matrix_subtype']) {
                    $emptyAnswer = false;

                    foreach ($this->subquestions as $k => $junk) {
                        foreach ($this->subquestions as $k => $junk) {
                            $strAnswer = '';

                            if (\is_array($arrAnswers[$k + 1])) {
                                $arrTmp = [];

                                foreach ($arrAnswers[$k + 1] as $kk => $v) {
                                    $arrTmp[] = $this->choices[$kk - 1];
                                }
                                $strAnswer = implode(' | ', $arrTmp);
                            }

                            if (0 === \strlen($strAnswer)) {
                                $emptyAnswer = true;
                            }
                        }
                    }

                    foreach ($this->subquestions as $k => $junk) {
                        $strAnswer = '';

                        if (\is_array($arrAnswers[$k + 1])) {
                            $arrTmp = [];

                            foreach ($arrAnswers[$k + 1] as $kk => $v) {
                                $arrTmp[] = $this->choices[$kk - 1];
                            }
                            // TODO: make delimiter configurable/intelligent, though '|' is a good default, breaks in Calc
                            $strAnswer = implode(' | ', $arrTmp);
                        }

                        if ($emptyAnswer) {
                            $strAnswer = ($k + 1).' - '.$strAnswer;
                        }

                        if (\strlen($strAnswer)) {
                            $exporter->setCellValue($sheet, $row, $col, [
                                Exporter::DATA => $strAnswer,
                                Exporter::CELLTYPE => is_numeric($strAnswer) ? Exporter::CELLTYPE_FLOAT : Exporter::CELLTYPE_STRING,
                                Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                                Exporter::TEXTWRAP => true,
                            ]);
                        }
                        ++$col;
                    }
                }
            }
            ++$row;
        }

        return $cells;
    }
}
