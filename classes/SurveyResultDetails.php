<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    survey
 * @license    LGPL
 */


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
		$class = "SurveyQuestion" . ucfirst($qtype["questiontype"]);
		$this->loadLanguageFile("tl_survey_result");
		$this->loadLanguageFile("tl_survey_question");
		$this->Template = new BackendTemplate('be_question_result_details');
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->hrefBack = \Environment::get('script') . '?do=' . \Input::get('do') . '&amp;key=cumulated&amp;id=' . $parent['pid'];
		if ($this->classFileExists($class))
		{
			$this->import($class);
			$question = new $class($qid);
			$this->Template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['detailsSummary'];
			$this->Template->heading = sprintf($GLOBALS['TL_LANG']['tl_survey_result']['detailsHeading'], $qid);
			$data = array();
			array_push($data, array("key" => 'ID:', 'value' => $question->id, 'keyclass' => 'first', 'valueclass' => 'last'));
			array_push($data, array("key" => $GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0].':', 'value' => specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$question->questiontype]), 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg'));
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
			$class = "SurveyQuestion" . ucfirst($row['questiontype']);
			if ($this->classFileExists($class))
			{
				$this->import($class);
				$question = new $class();
				$question->data = $row;
				$strUrl = \Environment::get('script') . '?do=' . \Input::get('do');
				$strUrl .= '&amp;key=details&amp;id=' . $question->id;
				array_push($data, array(
					'number' => $abs_question_no,
					'title' => specialchars($row['title']),
					'type' => specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$row['questiontype']]),
					'answered' => $question->statistics["answered"],
					'skipped' => $question->statistics["skipped"],
					'hrefdetails' => $strUrl,
					'titledetails' => specialchars(sprintf($GLOBALS['TL_LANG']['tl_survey_result']['details'][1], $question->id))
				));
			}
		}
		$this->Template = new BackendTemplate('be_survey_result_cumulated');
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->hrefBack = \Environment::get('script') . '?do=' . \Input::get('do');
		$hrefExport = \Environment::get('script') . '?do=' . \Input::get('do');
		$hrefExport .= '&amp;key=export&amp;id=' . \Input::get('id');
		$this->Template->export = $GLOBALS['TL_LANG']['tl_survey_result']['export'];
		$this->Template->hrefExport = $hrefExport;
		$this->Template->heading = specialchars($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);
		$this->Template->summary = 'cumulated results';
		$this->Template->data = $data;
		$this->Template->imgdetails = 'system/modules/survey_ce/assets/details.png';
		$this->Template->lngAnswered = $GLOBALS['TL_LANG']['tl_survey_question']['answered'];
		$this->Template->lngSkipped = $GLOBALS['TL_LANG']['tl_survey_question']['skipped'];
		return $this->Template->parse();
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
                    $objPHPExcel = new \PHPExcel();
                    $intRowCounter = 0;
                    $intColCounter = 0;
                    $sheet = utf8_decode($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);

                    
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
                                                    $col = $this->getCellTitle($cell['col']);
                                                    $row = $cell['row']+1;
                                                    
                                                    $pos = (string)$col.$row;
                                                    $objPHPExcel->getActiveSheet()->SetCellValue($pos,utf8_encode($cell['data']));
                                                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                                            }
                                    }
                            }
                    }
                    $objPHPExcel->getActiveSheet()->setTitle($sheet);
                    
                    
                    $objSurvey = $this->Database->prepare("SELECT title FROM tl_survey WHERE id = ?")
                            ->execute(\Input::get('id'));
                    if ($objSurvey->numRows == 1) {
                            $filename = $this->safefilename(htmlspecialchars_decode($objSurvey->title)) . ".xlsx";
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
                    exit;
		}
		$this->redirect(\Environment::get('script') . '?do=' . \Input::get('do'));
	}

	protected function safefilename($filename) 
	{
		$search = array('/ß/','/ä/','/Ä/','/ö/','/Ö/','/ü/','/Ü/','([^[:alnum:]._])');
		$replace = array('ss','ae','Ae','oe','Oe','ue','Ue','_');
		return preg_replace($search,$replace,$filename);
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
}

