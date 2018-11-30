<?php

namespace Hschottm\SurveyBundle;

/**
 * Class SurveyResultDetailsEx
 *
 * Provides methods to handle the detailed export of survey question results.
 *
 * @copyright  Georg Rehfeld 2010
 * @author     Georg Rehfeld <rehfeld@georg-rehfeld.de>
 * @package    Controller
 */
class SurveyResultDetailsEx extends SurveyResultDetails
{

	/**
	 * Exports the answers of all participants to all questions in a big matrix Excel table.
	 *
	 * Participants run top down, one row per participant. Questions run left to right,
	 * one or more column per question: open questions occupy just one column, but
	 * other types, like multiple choice or matrix questions take one column for
	 * every "subquestion"/choice. The answer, if any, is in the appropriate cell
	 * to the right of the participant and below the question/coice.
	 *
	 * Some additional data is exported as well, e.g. the IDs of questions and
	 * participants, page and question numbers, PIN/user-info, start/end date and
	 * the last page a participant has visited.
	 */
	public function exportResultsRaw(DataContainer $dc)
	{
		if (\Input::get('key') != 'exportraw')
		{
			return '';
		}

		$surveyID = \Input::get('id');
		$arrQuestions = $this->Database->prepare("
				SELECT   tl_survey_question.*,
				         tl_survey_page.title as pagetitle,
					     tl_survey_page.pid as parentID
				FROM     tl_survey_question, tl_survey_page
				WHERE    tl_survey_question.pid = tl_survey_page.id
				AND      tl_survey_page.pid = ?
				ORDER BY tl_survey_page.sorting, tl_survey_question.sorting")
			->execute($surveyID);
		if ($arrQuestions->numRows)
		{
			$this->loadLanguageFile('tl_survey_result');

			$xls = new \xlsexport();
			$sheet = utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['detailedResults']);
			$xls->addworksheet($sheet);

			$cells = $this->exportTopLeftArea($sheet);
			foreach ($cells as $cell)
			{
				if ($cell['colwidth'] > 0)
				{
					$xls->setcolwidth($sheet, $cell['col'], $cell['colwidth']);
					unset($cell['colwidth']);
				}
				$xls->setcell($cell);
			}

			$rowCounter = 8; // questionheaders will occupy that many rows
			$colCounter = 0;

			$participants = $this->fetchParticipants($surveyID);
			$cells = $this->exportParticipantRowHeaders($sheet, $rowCounter, $colCounter, $participants);
			foreach ($cells as $cell)
			{
				$xls->setcell($cell);
			}

			// init question counters
			$page_no = 0;
			$rel_question_no = 0;
			$abs_question_no = 0;
			$last_page_id = 0;

			while ($arrQuestions->next())
			{
				$row = $arrQuestions->row();

				// increase question numbering counters
				$abs_question_no++;
				$rel_question_no++;
				if ($last_page_id != $row['pid'])
				{
					// page id has changed, increase page no, reset question no on page
					$page_no++;
					$rel_question_no = 1;
					$last_page_id = $row['pid'];
				}
				$questionCounters = array(
					'page_no' => $page_no,
					'rel_question_no' => $rel_question_no,
					'abs_question_no' => $abs_question_no);

				$rowCounter = 0; // reset rowCounter for the question headers

				$class = "SurveyQuestion" . ucfirst($row["questiontype"]) .'Ex';
				if ($this->classFileExists($class))
				{
					$this->import($class);
					$question = new $class();
					$question->data = $row;
					$cells = $question->exportDetailsToExcel($xls, $sheet, $rowCounter, $colCounter, $questionCounters, $participants);
					foreach ($cells as $cell)
					{
						$xls->setcell($cell);
					}
				}
			}

			$objSurvey = $this->Database->prepare("SELECT title FROM tl_survey WHERE id = ?")
				->execute($surveyID);
			if ($objSurvey->numRows == 1)
			{
				$xls->sendFile($this->safefilename(htmlspecialchars_decode($objSurvey->title)) . "_detail.xls");
			}
			else
			{
				$xls->sendFile('survey_detail.xls');
			}
			exit;
		}
		$this->redirect(\Environment::get('script') . '?do=' . \Input::get('do'));
	}

	/**
	 * Exports some basic information in the unused top left area.
	 *
	 * @TODO: Quick and dirty implementation for the alpha version, make translatable / better.
	 */
	protected function exportTopLeftArea($sheet)
	{
		$result = array();

		// Legends for the question headers
		$row = 0;
		$col = 4;
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_id'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
				'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_nr'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_pg_nr'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_type'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_answered'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_skipped'] . ':')
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row++, 'col' => $col,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'hallign' => XLSXF_HALLIGN_RIGHT,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_title'] . ':')
		);

		// Legends for the participant headers
		$col = 0;
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'textwrap' => 1,
			'colwidth' => 6*256,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_id_gen'])
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'colwidth' => 5*256,
			'fontweight' => XLSFONT_BOLD, 'textwrap' => 1,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_sort'])
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'colwidth' => 14*256,
			'fontweight' => XLSFONT_BOLD, 'textwrap' => 1,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_date'])
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'textwrap' => 1,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_lastpage'])
		);
		$result[] = array(
			'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
			'colwidth' => 14*256,
			'bgcolor' => '#C0C0C0', 'color' => '#000000',
			'fontweight' => XLSFONT_BOLD, 'textwrap' => 1,
			'data' => utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['ex_question_participant'])
		);

		return $result;
	}

	/**
	 * Exports base/identifying information for all participants.
	 *
	 * Every participant has it's own row with several header columns.
	 */
	protected function exportParticipantRowHeaders($sheet, &$rowCounter, &$colCounter, $participants)
	{
		$result = array();
		$row = $rowCounter;
		foreach ($participants as $key => $participant)
		{
			$col = $colCounter;
			foreach ($participant as $k => $v)
			{
				if ($k == 'finished')
				{
					continue;
				}
				$cell = array(
					'sheetname' => $sheet, 'row' => $row, 'col' => $col++,
					'data' => $v,
				);
				switch ($k)
				{
					case 'id':
					case 'count':
					case 'lastpage':
						$cell['type'] = CELL_FLOAT;
						break;

					case 'display':
						if ($participant['finished'])
						{
							$cell['fontweight'] = XLSFONT_BOLD;
						}

					default:
						break;
				}
				$result[] = $cell;
			}
			$row++;
		}
		$rowCounter = $row;
		$colCounter = $col;
		return $result;
	}

	/**
	 * Fetches all participants of the given survey.
	 *
	 * @param int
	 * @return array
	 */
	public function fetchParticipants($surveyID)
	{
		$access = $this->Database->prepare("SELECT access FROM tl_survey WHERE id = ?")->execute($surveyID)->fetchAssoc();
		$objParticipant = $this->Database->prepare("
				SELECT    par.*,
				          mem.id        AS mem_id,
				          mem.firstname AS mem_firstname,
						  mem.lastname  AS mem_lastname,
						  mem.email     AS mem_email
				FROM      tl_survey_participant AS par
				LEFT JOIN tl_member             AS mem
				ON        par.uid = mem.id
				WHERE     par.pid = ?
				ORDER BY  par.lastpage DESC, par.finished DESC, par.tstamp DESC")
			->execute($surveyID);

		$result = array();
		$count = 0;
		while ($objParticipant->next())
		{
			$count++;
			if (strcmp($access['access'], 'nonanoncode') != 0)
			{
				$pin_uid = $objParticipant->pin;
				$display = $objParticipant->pin;
			}
			else
			{
				$pin_uid = $objParticipant->pin;
				$display = $objParticipant->mem_firstname . ' ' . $objParticipant->mem_lastname;
				if (strlen($objParticipant->mem_email))
				{
					$display .= ' <' . $objParticipant->mem_email . '>';
				}
				$display = utf8_decode($display);
			}
			$result[$pin_uid] = array(
				'id' => $objParticipant->id,
				'count' => $count,
				'date' => date('Y-m-d H:i:s', $objParticipant->tstamp),
				'lastpage' => $objParticipant->lastpage,
				'finished' => $objParticipant->finished,
				'display' => $display,
			);
		}
		return $result;
	}

}
