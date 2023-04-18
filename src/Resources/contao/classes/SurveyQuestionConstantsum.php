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

        if (isset($this->statistics['cumulated']) && \is_array($this->statistics['cumulated'])) {
            $result['statistics'] = $this->statistics;
            $result['choices'] = StringUtil::deserialize($this->arrData['sumchoices'], true);
            $result['cumulated'] = $this->statistics['cumulated'];
        }

        return $result;
    }

    public function getAnswersAsHTML()
    {
        if (!empty($resultData = $this->getResultData())) {
            $template = new FrontendTemplate('survey_answers_constantsum');
            $template->setData($resultData);
            $template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
            $template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
            $template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];

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
            $arrChoices = deserialize($this->arrData['sumchoices'], true);
            $counter = 1;

            foreach ($arrChoices as $id => $choice) {
                $exporter->setCellValue($sheet, $row + $counter - 1, $col, [Exporter::DATA => $choice]);
                ++$counter;
                $exporter->setCellValue($sheet, $row + $counter - 1, $col, [Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['nr_of_selections']]);
                ++$counter;
            }
            $counter = 1;
            $idx = 1;

            foreach ($arrChoices as $id => $choice) {
                $acounter = 3;

                foreach ($this->statistics['cumulated'][$idx] as $answervalue => $nrOfAnswers) {
                    $exporter->setCellValue($sheet, $row + $counter - 1, $acounter, [Exporter::DATA => $answervalue, Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT]);
                    $exporter->setCellValue($sheet, $row + $counter, $acounter, [Exporter::DATA => ($nrOfAnswers ?: 0), Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT]);
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
            return implode(', ', $arrAnswer);
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
                foreach ($arrAnswer as $answerkey => $answervalue) {
                    if(array_key_exists($answerkey, $cumulated)) {
                        if(array_key_exists($answervalue, $cumulated[$answerkey])) {
                            ++$cumulated[$answerkey][$answervalue];
                        } else {
                            $cumulated[$answerkey][$answervalue] = 1;
                        }
                    } else {
                        $cumulated[$answerkey] = [];
                    }
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
        $this->choices = deserialize($this->arrData['sumchoices'], true);

        foreach ($this->choices as $k => $v) {
            $this->choices[$k] = StringUtil::decodeEntities($v);
        }
        $numcols = \count($this->choices);
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
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype],
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
            // This is a strange case: a constant sum question with just one choice.
            // However, users do that (at least for testing) and have the right to do so.
            // Just add the one and only choice, without rotation ...
            $data = [
                Exporter::DATA => $this->choices[0],
                Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                Exporter::TEXTWRAP => true,
                Exporter::BORDERBOTTOM => Exporter::BORDER_THIN,
                Exporter::BORDERBOTTOMCOLOR => '#000000',
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
                    Exporter::DATA => $choice,
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

                foreach ($this->choices as $k => $choice) {
                    $strAnswer = '';

                    if (\array_key_exists($k + 1, $arrAnswers)) {
                        $strAnswer = $arrAnswers[$k + 1];
                    }

                    if (\strlen($strAnswer)) {
                        // Set value to numeric, when the coices are e.g. school grades '1'-'5', a common case (for me).
                        // Then the user is able to work with formulars in Excel/Calc, avarage for instance.
                        $exporter->setCellValue($sheet, $row, $col, [
                            Exporter::DATA => $strAnswer,
                            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
                            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_CENTER,
                            Exporter::TEXTWRAP => true,
                        ]);
                    }
                    ++$col;
                }
            }
            ++$row;
        }

        return $cells;
    }
}
