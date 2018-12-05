<?php

namespace Hschottm\SurveyBundle;

use Contao\DataContainer;
use Hschottm\ExcelXLSBundle\xlsexport;

/**
 * Class SurveyResultDetails
 *
 * Provide methods to handle the detail view of survey question results
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    Controller
 */
class SurveyResultDetails extends \Backend
{
	protected $blnSave = true;
	protected $useXLSX = false;

	/**
	 * Load the database object
	 */
	protected function __construct()
	{
		parent::__construct();
		if (in_array('php_excel', $this->Config->getActiveModules()))
		{
			$this->useXLSX = true;
		}
	}

	public function useXLSX()
	{
		return $this->useXLSX;
	}

	public function showDetails(DataContainer $dc)
	{
		if (\Input::get('key') != 'details')
		{
			return '';
		}
		$return = "";
		$qid = \Input::get('id');
		$qtype = $this->Database->prepare("SELECT questiontype, pid FROM tl_survey_question WHERE id = ?")
			->execute($qid)
			->fetchAssoc();
		$parent = $this->Database->prepare("SELECT pid FROM tl_survey_page WHERE id = ?")
			->execute($qtype['pid'])
			->fetchAssoc();
		$class = "Hschottm\\SurveyBundle\\SurveyQuestion" . ucfirst($qtype["questiontype"]);
		$this->loadLanguageFile("tl_survey_result");
		$this->loadLanguageFile("tl_survey_question");
		$this->Template = new \BackendTemplate('be_question_result_details');
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
    $this->Template->hrefBack = \Backend::addToUrl("key=cumulated&amp;id=" . $qtype['pid'], true, ['key','id']);
		if ($this->classFileExists($class))
		{
			$this->import($class);
			$question = new $class($qid);
			$this->Template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['detailsSummary'];
			$this->Template->heading = sprintf($GLOBALS['TL_LANG']['tl_survey_result']['detailsHeading'], $qid);
			$data = array();
			array_push($data, array("key" => 'ID:', 'value' => $question->id, 'keyclass' => 'first', 'valueclass' => 'last'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0].':', 'value' => \StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$question->questiontype]), 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['title'][0].':', 'value' => $question->title, 'keyclass' => 'first', 'valueclass' => 'last'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['question'][0].':', 'value' => $question->question, 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['answered'].':', 'value' => $question->statistics["answered"], 'keyclass' => 'first', 'valueclass' => 'last'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['skipped'].':', 'value' => $question->statistics["skipped"], 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_result']['answers'].':', 'value' => $question->getAnswersAsHTML(), 'keyclass' => 'first', 'valueclass' => 'last'));
			$this->Template->data = $data;
		}
		else
		{
			$return .= "ERROR: No statistical data found!";
		}
		return $this->Template->parse();
	}

	public function showCumulated(DataContainer $dc)
	{
		if (\Input::get('key') != 'cumulated')
		{
			return '';
		}
		$this->loadLanguageFile('tl_survey_result');
		$this->loadLanguageFile('tl_survey_question');
		$return = "";
		$objQuestion = $this->Database->prepare("SELECT tl_survey_question.*, tl_survey_page.title as pagetitle, tl_survey_page.pid as parentID FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting")
			->execute(\Input::get('id'));
		$data = array();
		$abs_question_no = 0;

		while ($row = $objQuestion->fetchAssoc())
		{
			$abs_question_no++;
			$class = "Hschottm\\SurveyBundle\\SurveyQuestion"  . ucfirst($row['questiontype']);

			if ($this->classFileExists($class))
			{
				$this->import($class);
				$question = new $class();
				$question->data = $row;
        $strUrl = \Backend::addToUrl("key=details&amp;id=" . $question->id, true, ['key','id']);
				array_push($data, array(
					'number' => $abs_question_no,
					'title' => \StringUtil::specialchars($row['title']),
					'type' => \StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$row['questiontype']]),
					'answered' => $question->statistics["answered"],
					'skipped' => $question->statistics["skipped"],
					'hrefdetails' => $strUrl,
					'titledetails' => \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['tl_survey_result']['details'][1], $question->id))
				));
			}
		}
		$this->Template = new \BackendTemplate('be_survey_result_cumulated');
    $this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->hrefBack = \Backend::addToUrl("", true, ['key','id']);
		$this->Template->export = $GLOBALS['TL_LANG']['tl_survey_result']['export'];
    $this->Template->hrefExport = \Backend::addToUrl("key=export&amp;id=" . \Input::get('id'), true, ['key','id']);
		$this->Template->heading = \StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);
		$this->Template->summary = 'cumulated results';
		$this->Template->data = $data;
		$this->Template->imgdetails = 'bundles/hschottmsurvey/images/details.png';
		$this->Template->lngAnswered = $GLOBALS['TL_LANG']['tl_survey_question']['answered'];
		$this->Template->lngSkipped = $GLOBALS['TL_LANG']['tl_survey_question']['skipped'];
		return $this->Template->parse();
	}

	protected function setValueXLSX($objPHPExcel, $cell)
	{
		$col = $this->getCellTitle($cell['col']);
		$row = $cell['row']+1;
		$pos = (string)$col.$row;
		$objPHPExcel->getActiveSheet()->SetCellValue($pos,utf8_encode($cell['data']));
		$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		$fill_array = array();
		$font_array = array();
		if ($cell['type'] > 0)
		{
			switch ($cell['type'])
			{
				case CELL_STRING:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					break;
				case CELL_FLOAT:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					break;
				case CELL_PICTURE:
					break;
				default:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
					break;
			}
		}
		else
		{
			$objPHPExcel->getActiveSheet()->getStyle($pos)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		}
		if (strlen($cell['bgcolor']) > 0)
		{
			$fill_array = array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => str_replace('#', '', $cell['bgcolor']))
					);
		}
		if (strlen($cell['color']) > 0)
		{
			$font_array['color'] = array('rgb' => str_replace('#', '', $cell['color']));
		}
		if (strlen($cell['hallign']) > 0)
		{
			switch ($cell['hallign'])
			{
				case XLSXF_HALLIGN_GENERAL:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
					break;
				case XLSXF_HALLIGN_LEFT:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					break;
				case XLSXF_HALLIGN_CENTER:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					break;
				case XLSXF_HALLIGN_RIGHT:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					break;
				case XLSXF_HALLIGN_FILL:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_FILL);
					break;
				case XLSXF_HALLIGN_JUSTIFY:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
					break;
				case XLSXF_HALLIGN_CACROSS:
					$objPHPExcel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS);
					break;
			}
		}
		if (strlen($cell['fontweight']) > 0)
		{
			if ($cell['fontweight'] == XLSFONT_BOLD)
			{
				$font_array['bold'] = true;
			}
		}
		$objPHPExcel->getActiveSheet()->getStyle($pos)->applyFromArray(
			array(
				'fill' => $fill_array,
				'font' => $font_array
			)
		);
	}

	public function exportResults(DataContainer $dc)
	{
		if (\Input::get('key') != 'export')
		{
			return '';
		}
		$this->loadLanguageFile('tl_survey_result');
		$arrQuestions = $this->Database->prepare("SELECT tl_survey_question.*, tl_survey_page.title as pagetitle, tl_survey_page.pid as parentID FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting")
			->execute(\Input::get('id'));
		if ($arrQuestions->numRows)
		{
			if ($this->useXLSX())
			{
				$objPHPExcel = new \PHPExcel();
				$sheet = utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);
				$intRowCounter = 0;
				$intColCounter = 0;
        // Generate Data
				$objPHPExcel->setActiveSheetIndex(0);

				while ($arrQuestions->next())
				{
					$row = $arrQuestions->row();
					$class = "SurveyQuestion" . ucfirst($row["questiontype"]);
					if ($this->classFileExists($class))
					{
						$this->import($class);
						$question = new $class();
						$question->data = $row;
						$cells = $question->exportDataToExcel($sheet, $intRowCounter);
						if (count($cells))
						{
							foreach ($cells as $cell)
							{
								$this->setValueXLSX($objPHPExcel, $cell);
							}
						}
					}
				}
				$objPHPExcel->getActiveSheet()->setTitle($sheet);

        $surveyModel = \Hschottm\SurveyBundle\SurveyModel::findOneBy('id', \Input::get('id'));
				if (null != $surveyModel)
				{
					$filename = \StringUtil::sanitizeFileName(htmlspecialchars_decode($surveyModel->title) . ".xlsx");
				} else {
					$filename = "survey.xlsx";
				}

				// Set Excel Properties
				$objPHPExcel->getProperties()->setCreator("Contao CMS");
				$objPHPExcel->getProperties()->setLastModifiedBy("Contao CMS");
				$objPHPExcel->getProperties()->setTitle($objSurvey->title);
				$objPHPExcel->getProperties()->setSubject($objSurvey->title);
				$objPHPExcel->getProperties()->setDescription($objSurvey->title);

				// Download the file
				$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');
				$objWriter->save('php://output');
				echo "";
				exit;
			}
			else
			{
				$xls = new xlsexport();
				$sheet = utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);
				$xls->addworksheet($sheet);
				$intRowCounter = 0;
				$intColCounter = 0;

				while ($arrQuestions->next())
				{
					$row = $arrQuestions->row();
					$class = "SurveyQuestion" . ucfirst($row["questiontype"]);
					if ($this->classFileExists($class))
					{
						$this->import($class);
						$question = new $class();
						$question->data = $row;
						$cells = $question->exportDataToExcel($sheet, $intRowCounter);
						if (count($cells))
						{
							foreach ($cells as $cell)
							{
								$xls->setcell($cell);
							}
						}
					}
				}

        $surveyModel = \Hschottm\SurveyBundle\SurveyModel::findOneBy('id', \Input::get('id'));
        if (null != $surveyModel)
				{
					$xls->sendFile(\StringUtil::sanitizeFileName(htmlspecialchars_decode($surveyModel->title) . ".xls"));
				}
				else
				{
					$xls->sendFile('survey.xls');
				}
			}
			exit;
		}
    //$href = str_replace(\Environment::get('queryString'), '', \Environment::get('requestUri')) . ((strlen(\Environment::get('queryString'))) ? '' : '?') . 'do=' . \Input::get('do');
    $href = \Backend::addToUrl("", true, ['key','id']);
		$this->redirect($href);
	}

	/**
	* Calculate the Excel cell address (A,...,Z,AA,AB,...) from a numeric index
	*
	*/
	private function getCellTitle($index)
	{
		$alphabet = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		if ($index < 26) return $alphabet[$index];
		return $alphabet[floor($index / 26)-1] . $alphabet[$index-(floor($index / 26)*26)];
	}

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

			$xls = new xlsexport();
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
				$xls->sendFile(\StringUtil::sanitizeFileName(htmlspecialchars_decode($objSurvey->title) . "_detail.xls"));
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
