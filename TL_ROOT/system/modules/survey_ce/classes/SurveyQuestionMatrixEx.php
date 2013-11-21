<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyQuestionMatrixEx
 *
 * @copyright  Georg Rehfeld 2010
 * @author     Georg Rehfeld <rehfeld@georg-rehfeld.de>
 */
class SurveyQuestionMatrixEx extends SurveyQuestionMatrix
{

	protected $subquestions = array();
	protected $choices = array();

	/**
	 * Import String library via superclass.
	 */
	public function __construct($question_id = 0)
	{
		parent::__construct($question_id);
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
	 * As a side effect the width for each column is calculated and set via the given $xls object.
	 * Row height is currently calculated/set ONLY for the row with subquestions, which is turned
	 * 90° ccw ... thus it is effectively also a text width calculation.
	 *
	 * Not setting row(/text) height explicitly in the general case is no problem in OpenOffice Calc 3.1,
	 * which does a good job here by default. However Excel 95/97 seems to do it worse,
	 * I can't test that currently. "Set optimal row height" might help users of Excel.
	 *
	 * @param object &$xls  the excel object to call methods on
	 * @param string $sheet  name of the worksheet
	 * @param int &$row  row to put a cell in
	 * @param int &$col  col to put a cell in
	 * @param array $questionNumbers  array with page and question numbers
	 * @param array $participants  array with all participant data
	 * @return array  the cells to be added to the export
	 *
	 * @TODO: eventually give out just indexes instead of choice strings to save width? Then the possible coices must be shown in the header.
	 */
	public function exportDetailsToExcel(&$xls, $sheet, &$row, &$col, $questionNumbers, $participants)
	{
/*
print "<pre>\n";
var_export(deserialize($this->arrData['matrixrows'], true));
var_export(deserialize($this->arrData['matrixcolumns'], true));
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
		$rotateInfo = array();
		$headerCells = $this->exportQuestionHeadersToExcel($xls, $sheet, $row, $col, $questionNumbers, $rotateInfo);
		$resultCells = $this->exportDetailResults($xls, $sheet, $row, $valueCol, $participants);

		foreach ($rotateInfo as $intRow => $arrText)
		{
			foreach ($arrText as $intCol => $strText)
			{
				$this->setRowHeightForRotatedText($xls, $sheet, $intRow, $intCol, $strText);
			}
		}

		return array_merge($headerCells, $resultCells);
	}

	/**
	 * Exports the column headers for a question of type 'matrix'.
	 *
	 * Several rows are returned, so that the user of the Excel file is able to
	 * use them for reference, filtering and sorting.
	 *
	 * @param object &$xls  the excel object to call methods on
	 * @param string $sheet  name of the worksheet
	 * @param int &$row  in/out row to put a cell in
	 * @param int &$col  in/out col to put a cell in
	 * @param array $questionNumbers  array with page and question numbers
	 * @param array &$rotateInfo  out param with row => text for later calculation of row height
	 * @return array  the cells to be added to the export
	 */
	protected function exportQuestionHeadersToExcel(&$xls, $sheet, &$row, &$col, $questionNumbers, &$rotateInfo)
	{
		$this->subquestions = deserialize($this->arrData['matrixrows'], true);
		foreach ($this->subquestions as $k => $v)
		{
			$this->subquestions[$k] = utf8_decode(\String::decodeEntities($v));
		}
		$numcols = count($this->subquestions);

		$this->choices = deserialize($this->arrData['matrixcolumns'], true);
		if ($this->arrData['addneutralcolumn'])
		{
			// TODO: i believe, the dash is better then the real text for the neutral column, make configurable?
			// $this->choices[] = $this->arrData['neutralcolumn'];
			$this->choices[] = '-';
		}
		foreach ($this->choices as $k => $v)
		{
			$this->choices[$k] = utf8_decode(\String::decodeEntities($v));
		}

		$result = array();

		// ID and question numbers
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'data' => $this->id, 'type' => CELL_FLOAT
		);
		$row++;
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'fontstyle' => XLSFONT_STYLE_ITALIC,
			'data' => $questionNumbers['abs_question_no'], 'type' => CELL_FLOAT
		);
		$row++;
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_CENTER,
			'data' => $questionNumbers['page_no'] . '.' . $questionNumbers['rel_question_no']
		);
		$row++;

		// question type
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'data' =>
				utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->questiontype]) . ', ' .
				utf8_decode($GLOBALS['TL_LANG']['tl_survey_question'][$this->arrData['matrix_subtype']])
		);
		$row++;

		// answered and skipped info, retrieves all answers as a side effect
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'data' => $this->statistics['answered'], 'type' => CELL_FLOAT
		);
		$row++;
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'data' => $this->statistics['skipped'], 'type' => CELL_FLOAT
		);
		$row++;

		// question title
		($numcols > 1) && $xls->merge_cells($sheet, $row, $row, $col, $col + $numcols - 1);
		$title = utf8_decode(\String::decodeEntities($this->title)) . ($this->arrData['obligatory'] ? ' *' : '');
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col,
			'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
			'data' => $title
		);
		// Guess a minimum column width for the title
		$minColWidthTitle = max(
			($this->getLongestWordLen($title) + 3) * 256,
			$xls->getcolwidth($sheet, $col)
		);
		$row++;

		if ($numcols == 1)
		{
			// This is a strange case: a matrix question with just one subquestion.
			// However, users do that (at least for testing) and have the right to do so.
			// Just add the one and only subquestion, without rotation ...
			$result[] = array(
				'sheetname' => $sheet, 'row' => $row, 'col' => $col,
				'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
				'borderbottom' => XLSXF_BORDER_THIN, 'borderbottomcolor' => '#000000',
				'data' => $this->subquestions[0]
			);

			// ... and recalculate the col width
			$minColWidth = max(
				($this->getLongestWordLen($this->subquestions[0]) + 3) * 256,
				$minColWidthTitle
			);
			$xls->setcolwidth($sheet, $col, $minColWidth);
			$col++;
		}
		else
		{
			// output all subquestion columns
			$rotateInfo[$row] = array();
			$narrowWidth = 2 * 640;
			$sumWidth = 0;
			foreach ($this->subquestions as $key => $subquestion)
			{
				$result[] = array(
					'sheetname' => $sheet, 'row' => $row, 'col' => $col,
					'textrotate' => XLSXF_TEXTROTATION_COUNTERCLOCKWISE,
					'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
					'borderbottom' => XLSXF_BORDER_THIN, 'borderbottomcolor' => '#000000',
					'data' => $subquestion
				);
				// make cols as narrow as possible, but wide enough for the title in the merged cells above
				$minColWidth = max(
					intval($minColWidthTitle / count($this->subquestions)),
					$narrowWidth
				);
				$xls->setcolwidth($sheet, $col, $minColWidth);
				$rotateInfo[$row][$col] = $subquestion;
				$col++;
			}
		}
		$row++;

		return $result;
	}

	/**
	 * Exports all results/answers to the question at hand.
	 *
	 * Sets some column widthes as a side effect.
	 *
	 * @param object &$xls  the excel object to call methods on
	 * @param string $sheet  name of the worksheet
	 * @param int &$row  row to put a cell in
	 * @param int &$col  col to put a cell in
	 * @param array $participants  array with all participant data
	 * @return array  the cells to be added to the export
	 *
	 * @TODO: make alignment and max colwidth configurable in dcaconfig.php ?
	 */
	protected function exportDetailResults(&$xls, $sheet, &$row, &$col, $participants)
	{
		$cells = array();
		$startCol = $col;
		foreach ($participants as $key => $value)
		{
			$data = false;
			if (strlen($this->statistics['participants'][$key]['result']))
			{
				// future state of survey_ce
				$data = $this->statistics['participants'][$key]['result'];
			}
			elseif (strlen($this->statistics['participants'][$key][0]['result']))
			{
				// current state of survey_ce: additional subarray with always 1 entry
				$data = $this->statistics['participants'][$key][0]['result'];
			}
			if ($data)
			{
				$col = $startCol;
				$arrAnswers = deserialize($data, true);
				if ($this->arrData['matrix_subtype'] == 'matrix_singleresponse')
				{
					foreach ($this->subquestions as $k => $junk)
					{
						$strAnswer = '';
						if (array_key_exists($k + 1, $arrAnswers))
						{
							// These 1 based array keys and values Helmut used here for the answers drive me crazy!
							// 1 based would be perfectly OK in e.g. Erlang, where almost everything is 1 based,
							// but not in PHP, where numerical arrays are 0 based typically.
							$choice_key = $arrAnswers[$k + 1] - 1;
							if (array_key_exists($choice_key, $this->choices))
							{
								$strAnswer = $this->choices[$choice_key];
							}
						}
						if (strlen($strAnswer))
						{
							// Set value to numeric, when the coices are e.g. school grades '1'-'5', a common case (for me).
							// Then the user is able to work with formulars in Excel/Calc, avarage for instance.
							$cells[] = array(
								'sheetname' => $sheet, 'row' => $row, 'col' => $col,
								'type' => is_numeric($strAnswer) ? CELL_FLOAT : CELL_STRING,
								'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
								'data' => $strAnswer
							);
							// Guess a minimum column width for the answer column.
							$minColWidth = max(
								($this->getLongestWordLen($strAnswer) + 3) * 256,
								$xls->getcolwidth($sheet, $col),
								min (strlen($strAnswer) / 8 * 256, 40 * 256)
							);
							$xls->setcolwidth($sheet, $col, $minColWidth);
						}
						$col++;
					}
				}
				elseif ($this->arrData['matrix_subtype'] == 'matrix_multipleresponse')
				{
					foreach ($this->subquestions as $k => $junk)
					{
						$strAnswer = '';
						if (is_array($arrAnswers[$k + 1]))
						{
							$arrTmp = array();
							foreach ($arrAnswers[$k + 1] as $kk => $v)
							{
								$arrTmp[] = $this->choices[$kk - 1];
							}
							// TODO: make delimiter configurable/intelligent, though '|' is a good default, breaks in Calc
							$strAnswer = implode(' | ', $arrTmp);
						}
						if (strlen($strAnswer))
						{
							$cells[] = array(
								'sheetname' => $sheet, 'row' => $row, 'col' => $col,
								'textwrap' => 1, 'hallign' => XLSXF_HALLIGN_CENTER,
								'data' => $strAnswer
							);
							// Guess a minimum column width for the answer column.
							$minColWidth = max(
								($this->getLongestWordLen($strAnswer) + 3) * 256,
								$xls->getcolwidth($sheet, $col),
								min (strlen($strAnswer) / 8 * 256, 40 * 256)
							);
							$xls->setcolwidth($sheet, $col, $minColWidth);
						}
						$col++;
					}
				}
			}
			$row++;
		}
		return $cells;
	}

	/**
	 * Guesses and sets a height for the given row containing rotatet text (90° cw or ccw).
	 *
	 * The guess assumes the default font and is based on the existing col width.
	 *
	 * @param object &$xls  the excel object to call methods on
	 * @param string $sheet  name of the worksheet
	 * @param int $row  row to calculate/set the height for
	 * @param int $col  col to consider in the calculation (it's current width)
	 * @param string $text  the text to consider in calculation
	 * @return void
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
			intval((utf8_strlen($text) + 3) * $hscale / round($xls->getcolwidth($sheet, $col) / 640)),
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
		foreach ($arrChunks as $strChunk)
		{
			$len = utf8_strlen($strChunk);
			if ($len > $result)
			{
				$result = $len;
			}
		}
		return $result;
	}

}

