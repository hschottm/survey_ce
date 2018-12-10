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
 * Class SurveyQuestionMultiplechoice.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyQuestionMultiplechoice extends SurveyQuestion
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
            $template = new \FrontendTemplate('survey_answers_multiplechoice');
            $template->statistics = $this->statistics;
            $template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedSummary'];
            $template->answer = $GLOBALS['TL_LANG']['tl_survey_result']['answer'];
            $template->nrOfSelections = $GLOBALS['TL_LANG']['tl_survey_result']['nrOfSelections'];
            $template->choices = (0 !== strcmp($this->arrData['multiplechoice_subtype'], 'mc_dichotomous')) ? deserialize($this->arrData['choices'], true) : [0 => $GLOBALS['TL_LANG']['tl_survey_question']['yes'], 1 => $GLOBALS['TL_LANG']['tl_survey_question']['no']];
            $template->other = ($this->arrData['addother']) ? true : false;
            $template->othertitle = \StringUtil::specialchars($this->arrData['othertitle']);
            $otherchoices = [];
            if (\count($this->statistics['cumulated']['other'])) {
                foreach ($this->statistics['cumulated']['other'] as $value) {
                    ++$otherchoices[\StringUtil::specialchars($value)];
                }
            }
            $template->otherchoices = $otherchoices;

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
        $exporter->setCellValue($sheet, $row + 1, 0, [ExcelExporter::DATA => $GLOBALS['TL_LANG']['tl_survey_question']['nrOfSelections'], ExcelExporter::BGCOLOR => $this->titlebgcolor, ExcelExporter::COLOR => $this->titlecolor, ExcelExporter::FONTWEIGHT => ExcelExporter::FONTWEIGHT_BOLD]);

        $arrChoices = (0 !== strcmp($this->arrData['multiplechoice_subtype'], 'mc_dichotomous')) ? deserialize($this->arrData['choices'], true) : [0 => $GLOBALS['TL_LANG']['tl_survey_question']['yes'], 1 => $GLOBALS['TL_LANG']['tl_survey_question']['no']];
        $col = 1;
        foreach ($arrChoices as $id => $choice) {
            $exporter->setCellValue($sheet, $row, $col, [ExcelExporter::DATA => $choice]);
            $exporter->setCellValue($sheet, $row + 1, $col++, [ExcelExporter::DATA => (($this->statistics['cumulated'][$id + 1]) ? $this->statistics['cumulated'][$id + 1] : 0), ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
        }
        if ($this->arrData['addother']) {
            $exporter->setCellValue($sheet, $row, $col, [ExcelExporter::DATA => $this->arrData['othertitle']]);
            $exporter->setCellValue($sheet, $row + 1, $col++, [ExcelExporter::DATA => \count($this->statistics['cumulated']['other']), ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
            if (\count($this->statistics['cumulated']['other'])) {
                $otherchoices = [];
                foreach ($this->statistics['cumulated']['other'] as $value) {
                    ++$otherchoices[$value];
                }
                foreach ($otherchoices as $key => $count) {
                    $exporter->setCellValue($sheet, $row, $col, [ExcelExporter::DATA => $key, ExcelExporter::BGCOLOR => $this->otherbackground, ExcelExporter::COLOR => $this->othercolor]);
                    $exporter->setCellValue($sheet, $row + 1, $col++, [ExcelExporter::DATA => $count, ExcelExporter::CELLTYPE => ExcelExporter::CELLTYPE_FLOAT]);
                }
            }
        }
        $row += 3;
    }

    /**
     * Exports multiple choice question headers and all existing answers.
     *
     * Questions of subtype mc_dichotomous occupy one column and get yes/no values as answers.
     *
     * Questions of subtype mc_singleresponse also occupy one column only and the participants
     * choice as answer. If there is the optinal "other answer" present and choosen, the value
     * will be the participants input prepended by the title of the other answer.
     *
     * Questions of subtype mc_multipleresponse occupy one column for every choice.
     * All possible coices are given in the header (turned ccw) and 'x' will be the value, if
     * choosen by the participant. The optional "other answer" gets its own column with the
     * participants entry as value. Common question headers, e.g. the id, question-numbers,
     * title are exported in merged cells spanning all choice columns.
     *
     * As a side effect the width for each column is calculated and set via the given $xls object.
     * Row height is currently calculated/set ONLY for the row with subquestions/choices, which is turned
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
        $valueCol = $col;
        $rotateInfo = [];
        $headerCells = $this->exportQuestionHeadersToExcel($xls, $sheet, $row, $col, $questionNumbers, $rotateInfo);
        $resultCells = $this->exportDetailResults($xls, $sheet, $row, $valueCol, $participants);

        foreach ($rotateInfo as $intRow => $arrText) {
            foreach ($arrText as $intCol => $strText) {
                $this->setRowHeightForRotatedText($xls, $sheet, $intRow, $intCol, $strText);
            }
        }

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

    protected function calculateAnsweredSkipped(&$objResult)
    {
        $this->arrStatistics = [];
        $this->arrStatistics['answered'] = 0;
        $this->arrStatistics['skipped'] = 0;
        while ($objResult->next()) {
            $id = (\strlen($objResult->pin)) ? $objResult->pin : $objResult->uid;
            $this->arrStatistics['participants'][$id][] = $objResult->row();
            $this->arrStatistics['answers'][] = $objResult->result;
            if (\strlen($objResult->result)) {
                $arrAnswer = deserialize($objResult->result, true);
                $found = false;
                if (\is_array($arrAnswer['value'])) {
                    foreach ($arrAnswer['value'] as $answervalue) {
                        if (\strlen($answervalue)) {
                            $found = true;
                        }
                    }
                } else {
                    if (\strlen($arrAnswer['value'])) {
                        $found = true;
                    }
                }
                if (\strlen($arrAnswer['other'])) {
                    $found = true;
                }
                if ($found) {
                    ++$this->arrStatistics['answered'];
                } else {
                    ++$this->arrStatistics['skipped'];
                }
            } else {
                ++$this->arrStatistics['skipped'];
            }
        }
    }

    protected function calculateCumulated()
    {
        $cumulated = [];
        $cumulated['other'] = [];
        foreach ($this->arrStatistics['answers'] as $answer) {
            $arrAnswer = deserialize($answer, true);
            if (\is_array($arrAnswer['value'])) {
                foreach ($arrAnswer['value'] as $answervalue) {
                    if (\strlen($answervalue)) {
                        $cumulated[$answervalue]++;
                    }
                }
            } else {
                if (\strlen($arrAnswer['value'])) {
                    $cumulated[$arrAnswer['value']]++;
                }
            }
            if (\strlen($arrAnswer['other'])) {
                array_push($cumulated['other'], $arrAnswer['other']);
            }
        }
        $this->arrStatistics['cumulated'] = $cumulated;
    }

    /**
     * Exports the column headers for a question of type 'multiple choice'.
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
        $this->choices = ('mc_dichotomous' === $this->arrData['multiplechoice_subtype'])
            ? [
                    0 => $GLOBALS['TL_LANG']['tl_survey_question']['yes'],
                    1 => $GLOBALS['TL_LANG']['tl_survey_question']['no'],
                ]
            : deserialize($this->arrData['choices'], true);
        if ($this->arrData['addother']) {
            $this->choices[] = preg_replace('/[-=>:\s]+$/', '', $this->arrData['othertitle']);
        }
        $numcols = ('mc_multipleresponse' === $this->arrData['multiplechoice_subtype']) ? \count($this->choices) : 1;

        $result = [];

        // ID and question numbers
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'data' => $this->id, 'type' => CELL_FLOAT,
        ];
        ++$row;
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'fontstyle' => XLSFONT_STYLE_ITALIC,
            'data' => $questionNumbers['abs_question_no'], 'type' => CELL_FLOAT,
        ];
        ++$row;
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_CENTER,
            'data' => $questionNumbers['page_no'].'.'.$questionNumbers['rel_question_no'],
        ];
        ++$row;

        // question type
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]).', '.
                utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->arrData['multiplechoice_subtype']]),
        ];
        ++$row;

        // answered and skipped info, retrieves all answers as a side effect
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'data' => $this->statistics['answered'], 'type' => CELL_FLOAT,
        ];
        ++$row;
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'data' => $this->statistics['skipped'], 'type' => CELL_FLOAT,
        ];
        ++$row;

        // question title
        ($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
        $title = utf8_decode(StringUtil::decodeEntities($this->title)).($this->arrData['obligatory'] ? ' *' : '');
        $result[] = [
            'sheetname' => $sheet, 'row' => $row, 'col' => $col,
            'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
            'data' => $title,
        ];
        // Guess a minimum column width for the title
        $minColWidthTitle = max(
            ($this->getLongestWordLen($title) + 3) * 256,
            $xls->getcolwidth($sheet, $col)
        );
        ++$row;

        if (1 === $numcols) {
            $xls->setcolwidth($sheet, $col, $minColWidthTitle);

            // add an empty cell, just for the formatting
            $result[] = [
                'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                'textrotate' => XLSXF_TEXTROTATION_COUNTERCLOCKWISE, 'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                'borderbottom' => XLSXF_BORDER_THIN, 'borderbottomcolor' => '#000000',
                'data' => '',
            ];
            ++$col;
        } else {
            // output all choice columns
            $rotateInfo[$row] = [];
            $narrowWidth = 2 * 640;
            $sumWidth = 0;
            foreach ($this->choices as $key => $choice) {
                $result[] = [
                    'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                    'textrotate' => ($this->arrData['addother'] && ($key === \count($this->choices) - 1))
                        ? XLSXF_TEXTROTATION_NOROTATION
                        : XLSXF_TEXTROTATION_COUNTERCLOCKWISE,
                    'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                    'borderbottom' => XLSXF_BORDER_THIN, 'borderbottomcolor' => '#000000',
                    'data' => utf8_decode($choice),
                ];
                if ($this->arrData['addother']) {
                    // the "other" column will take care of a good col widht for the merged cells above,
                    // so we set the normal choice columns as narrow as possible, accounting for 1-2 lines of ccw text
                    if ($key < \count($this->choices) - 1) {
                        $sumWidth += $narrowWidth;
                        $xls->setcolwidth($sheet, $col, $narrowWidth);
                    } else {
                        // Guess a minimum column width for the "other" column
                        $minColWidth = max(
                            ($this->getLongestWordLen($choice) + 3) * 256,
                            $narrowWidth,
                            $minColWidthTitle - $sumWidth
                        );
                        $xls->setcolwidth($sheet, $col, $minColWidth);
                    }
                } else {
                    // only 'x' values will be given out, make cols as narrow as possible, but wide enough for the
                    // title in the merged cells above
                    $minColWidth = max(
                        (int) ($minColWidthTitle / \count($this->choices)),
                        $narrowWidth
                    );
                    $xls->setcolwidth($sheet, $col, $minColWidth);
                }
                $rotateInfo[$row][$col] = $choice;
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
    protected function exportDetailResults(&$xls, $sheet, &$row, &$col, $participants)
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
                if ('mc_dichotomous' === $this->arrData['multiplechoice_subtype']) {
                    $cells[] = [
                        'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                        'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                        'data' => utf8_decode($this->choices[$arrAnswers['value'] - 1]),
                    ];
                } elseif ('mc_singleresponse' === $this->arrData['multiplechoice_subtype']) {
                    $strAnswer = utf8_decode($this->choices[$arrAnswers['value'] - 1]);
                    if (($this->arrData['addother']) && ($arrAnswers['value'] === \count($this->choices))) {
                        $strAnswer .= ': '.utf8_decode(StringUtil::decodeEntities($arrAnswers['other']));
                    }
                    $cells[] = [
                        'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                        'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                        'data' => $strAnswer,
                    ];
                    // Guess a minimum column width.
                    $minColWidth = max(
                        ($this->getLongestWordLen($strAnswer) + 3) * 256,
                        $xls->getcolwidth($sheet, $col),
                        min(\strlen($strAnswer) / 8 * 256, 40 * 256)
                    );
                    $xls->setcolwidth($sheet, $col, $minColWidth);
                } elseif ('mc_multipleresponse' === $this->arrData['multiplechoice_subtype']) {
                    foreach ($this->choices as $k => $v) {
                        $strAnswer = (\is_array($arrAnswers['value']) && array_key_exists($k + 1, $arrAnswers['value']))
                            ? ($this->arrData['addother'] && ($k + 1 === \count($this->choices)))
                                ? utf8_decode(StringUtil::decodeEntities($arrAnswers['other']))
                                : 'x'
                            : '';
                        if (\strlen($strAnswer)) {
                            $cells[] = [
                                'sheetname' => $sheet, 'row' => $row, 'col' => $col,
                                'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
                                'data' => $strAnswer,
                            ];
                            if ('x' !== $strAnswer) {
                                // Guess a minimum column width for the "other" column.
                                $minColWidth = max(
                                    ($this->getLongestWordLen($strAnswer) + 3) * 256,
                                    $xls->getcolwidth($sheet, $col),
                                    min(\strlen($strAnswer) / 8 * 256, 40 * 256)
                                );
                                $xls->setcolwidth($sheet, $col, $minColWidth);
                            }
                        }
                        ++$col;
                    }
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
