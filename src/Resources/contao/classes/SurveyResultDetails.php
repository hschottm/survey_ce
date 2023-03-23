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

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\DataContainer;
use Contao\Environment;
use Contao\Input;
use Contao\StringUtil;
use Hschottm\SurveyBundle\Export\Exporter;
use Hschottm\SurveyBundle\Export\ExportHelper;

/**
 * Class SurveyResultDetails.
 *
 * Provide methods to handle the detail view of survey question results
 *
 * @copyright  Helmut Schottmüller 2009-2018
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 */
class SurveyResultDetails extends Backend
{
    protected $blnSave = true;

    /**
     * Load the database object.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    public function showDetails(DataContainer $dc)
    {
        if ('details' !== Input::get('key')) {
            return '';
        }
        $return = '';
        $qid = (int)Input::get('id');
        $qtype = $this->Database->prepare('SELECT questiontype, pid FROM tl_survey_question WHERE id = ?')
            ->execute($qid)
            ->fetchAssoc()
        ;
        $parent = $this->Database->prepare('SELECT pid FROM tl_survey_page WHERE id = ?')
            ->execute($qtype['pid'])
            ->fetchAssoc()
        ;
        $this->loadLanguageFile('tl_survey_result');
        $this->loadLanguageFile('tl_survey_question');
        $this->Template = new BackendTemplate('be_question_result_details');
        $this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->hrefBack = Backend::addToUrl('key=cumulated&amp;id='.$parent['pid'], true, ['key', 'id']);

        $question = SurveyQuestion::createInstance($qid, $qtype['questiontype']);

        if ($question) {
            $this->Template->summary = $GLOBALS['TL_LANG']['tl_survey_result']['detailsSummary'];
            $this->Template->heading = sprintf($GLOBALS['TL_LANG']['tl_survey_result']['detailsHeading'], $qid);
            $data = [];
            array_push($data, ['key' => 'ID:', 'value' => $question->id, 'keyclass' => 'first', 'valueclass' => 'last']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_question']['questiontype'][0].':', 'value' => StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$question->questiontype]), 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_question']['title'][0].':', 'value' => $question->title, 'keyclass' => 'first', 'valueclass' => 'last']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_question']['question'][0].':', 'value' => $question->question, 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_question']['answered'].':', 'value' => $question->statistics['answered'], 'keyclass' => 'first', 'valueclass' => 'last']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_question']['skipped'].':', 'value' => $question->statistics['skipped'], 'keyclass' => 'first tl_bg', 'valueclass' => 'last tl_bg']);
            array_push($data, ['key' => $GLOBALS['TL_LANG']['tl_survey_result']['answers'].':', 'value' => $question->getAnswersAsHTML(), 'keyclass' => 'first', 'valueclass' => 'last']);
            $this->Template->data = $data;
        } else {
            $return .= 'ERROR: No statistical data found!';
        }

        return $this->Template->parse();
    }

    public function showCumulated(DataContainer $dc)
    {
        if ('cumulated' !== Input::get('key')) {
            return '';
        }
        $this->loadLanguageFile('tl_survey_result');
        $this->loadLanguageFile('tl_survey_question');
        $return = '';
        $objQuestion = $this->Database->prepare('SELECT tl_survey_question.*, tl_survey_page.title as pagetitle, tl_survey_page.pid as parentID FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
            ->execute(Input::get('id'))
        ;
        $data = [];
        $abs_question_no = 0;

        $categoryCount = [];
        $collectCategories = false;

        $surveyModel = SurveyModel::findByPk(Input::get('id'));

        if ($surveyModel && $surveyModel->useResultCategories) {
            $collectCategories = true;
        }

        while ($row = $objQuestion->fetchAssoc()) {
            ++$abs_question_no;

            $question = SurveyQuestion::createInstance(0, $row['questiontype']);

            if ($question) {
                $question->data = $row;
                $strUrl = Backend::addToUrl('key=details&amp;id='.$question->id, true, ['key', 'id']);
                array_push($data, [
                    'number' => $abs_question_no,
                    'title' => StringUtil::specialchars($row['title']),
                    'type' => StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question'][$row['questiontype']]),
                    'answered' => $question->statistics['answered'],
                    'skipped' => $question->statistics['skipped'],
                    'hrefdetails' => $strUrl,
                    'titledetails' => StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['tl_survey_result']['details'][1], $question->id)),
                ]);

                if ($collectCategories && $question instanceof SurveyQuestionMultiplechoice) {
                    foreach ($question->getResultData()['categories'] as $key => $value) {
                        $categoryCount[$key] = ($categoryCount[$key] ?? 0) + $value;
                    }
                }
            }
        }
        $this->Template = new BackendTemplate('be_survey_result_cumulated');
        $this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->hrefBack = Backend::addToUrl('', true, ['key', 'id']);
        $this->Template->export = $GLOBALS['TL_LANG']['tl_survey_result']['export'];
        $this->Template->hrefExport = Backend::addToUrl('key=export&amp;id='.Input::get('id'), true, ['key', 'id']);
        $this->Template->heading = StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults']);
        $this->Template->summary = 'cumulated results';
        $this->Template->data = $data;
        $this->Template->imgdetails = 'bundles/hschottmsurvey/images/details.png';
        $this->Template->lngAnswered = $GLOBALS['TL_LANG']['tl_survey_question']['answered'];
        $this->Template->lngSkipped = $GLOBALS['TL_LANG']['tl_survey_question']['skipped'];

        if ($collectCategories && !empty($categoryCount)) {
            $resultCount = array_sum($categoryCount);
            $categories = [];

            foreach ($categoryCount as $id => $count) {
                if ($categoryName = $surveyModel->getCategoryName((int) $id)) {
                    $categories[$id] = [
                        'name' => $categoryName,
                        'count' => $count,
                        'percent' => ceil($count / $resultCount * 100),
                    ];
                }
            }
            $this->Template->categories = $categories;
        }

        return $this->Template->parse();
    }

    public function exportResults(DataContainer $dc)
    {
        if ('export' !== Input::get('key')) {
            return '';
        }
        $this->loadLanguageFile('tl_survey_result');
        $arrQuestions = $this->Database->prepare('SELECT tl_survey_question.*, tl_survey_page.title as pagetitle, tl_survey_page.pid as parentID FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
            ->execute(Input::get('id'))
        ;

        if ($arrQuestions->numRows) {
            $exporter = ExportHelper::getExporter();
            $sheet = $GLOBALS['TL_LANG']['tl_survey_result']['cumulatedResults'];
            $intRowCounter = 0;
            $intColCounter = 0;
            $exporter->addSheet($sheet);

            while ($arrQuestions->next()) {
                $row = $arrQuestions->row();
                $class = 'Hschottm\SurveyBundle\SurveyQuestion'.ucfirst($row['questiontype']);

                if ($this->classFileExists($class)) {
                    $this->import($class);
                    $question = new $class();
                    $question->data = $row;
                    $question->exportDataToExcel($exporter, $sheet, $intRowCounter);
                }
            }

            $surveyModel = SurveyModel::findOneBy('id', Input::get('id'));

            if (null !== $surveyModel) {
                $filename = $surveyModel->title;
            } else {
                $filename = 'survey';
            }
            $exporter->setFilename($filename);
            $exporter->sendFile($surveyModel->title, $surveyModel->title, $surveyModel->title, 'Contao CMS', 'Contao CMS');
        }
        $href = Backend::addToUrl('', true, ['key', 'id']);
        $this->redirect($href);
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
        if ('exportraw' !== Input::get('key')) {
            return '';
        }

        $surveyID = Input::get('id');
        $arrQuestions = $this->Database->prepare('
				SELECT   tl_survey_question.*,
				         tl_survey_page.title as pagetitle,
					     tl_survey_page.pid as parentID
				FROM     tl_survey_question, tl_survey_page
				WHERE    tl_survey_question.pid = tl_survey_page.id
				AND      tl_survey_page.pid = ?
				ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
            ->execute($surveyID)
        ;

        if ($arrQuestions->numRows) {
            $this->loadLanguageFile('tl_survey_result');

            $exporter = ExportHelper::getExporter();
            $sheet = $GLOBALS['TL_LANG']['tl_survey_result']['detailedResults'];
            $exporter->addSheet($sheet);

            $this->exportTopLeftArea($exporter, $sheet);

            $rowCounter = 8; // questionheaders will occupy that many rows
            $colCounter = 0;

            $participants = $this->fetchParticipants($surveyID);
            $this->exportParticipantRowHeaders($exporter, $sheet, $rowCounter, $colCounter, $participants);

            // init question counters
            $page_no = 0;
            $rel_question_no = 0;
            $abs_question_no = 0;
            $last_page_id = 0;

            while ($arrQuestions->next()) {
                $row = $arrQuestions->row();

                // increase question numbering counters
                ++$abs_question_no;
                ++$rel_question_no;

                if ($last_page_id !== $row['pid']) {
                    // page id has changed, increase page no, reset question no on page
                    ++$page_no;
                    $rel_question_no = 1;
                    $last_page_id = $row['pid'];
                }
                $questionCounters = [
                    'page_no' => $page_no,
                    'rel_question_no' => $rel_question_no,
                    'abs_question_no' => $abs_question_no, ];

                $rowCounter = 0; // reset rowCounter for the question headers

                $class = 'Hschottm\SurveyBundle\SurveyQuestion'.ucfirst($row['questiontype']);

                if ($this->classFileExists($class)) {
                    $this->import($class);
                    $question = new $class();
                    $question->data = $row;
                    $question->exportDetailsToExcel($exporter, $sheet, $rowCounter, $colCounter, $questionCounters, $participants);
                }
            }

            $surveyModel = SurveyModel::findOneBy('id', $surveyID);

            if (null !== $surveyModel) {
                $filename = $surveyModel->title.'_detail';
            } else {
                $filename = 'survey_detail';
            }
            $exporter->setFilename($filename);
            $exporter->sendFile($surveyModel->title, $surveyModel->title, $surveyModel->title, 'Contao CMS', 'Contao CMS');
            exit;
        }
        $this->redirect(Environment::get('script').'?do='.Input::get('do'));
    }

    /**
     * Fetches all participants of the given survey.
     *
     * @param int
     * @param mixed $surveyID
     *
     * @return array
     */
    public function fetchParticipants($surveyID)
    {
        $access = $this->Database->prepare('SELECT access FROM tl_survey WHERE id = ?')->execute($surveyID)->fetchAssoc();
        $objParticipant = $this->Database->prepare('
				SELECT    par.*,
				          mem.id        AS mem_id,
				          mem.firstname AS mem_firstname,
						  mem.lastname  AS mem_lastname,
						  mem.email     AS mem_email
				FROM      tl_survey_participant AS par
				LEFT JOIN tl_member             AS mem
				ON        par.uid = mem.id
				WHERE     par.pid = ?
				ORDER BY  par.lastpage DESC, par.finished DESC, par.tstamp DESC')
            ->execute($surveyID)
        ;

        $result = [];
        $count = 0;

        while ($objParticipant->next()) {
            ++$count;

            if (0 !== strcmp($access['access'], 'nonanoncode')) {
                $pin_uid = $objParticipant->pin;
                $display = $objParticipant->pin;
            } else {
                $pin_uid = $objParticipant->pin;
                $display = $objParticipant->mem_firstname.' '.$objParticipant->mem_lastname;

                if (\strlen($objParticipant->mem_email)) {
                    $display .= ' <'.$objParticipant->mem_email.'>';
                }

                if (version_compare(VERSION, '4.9', '<')) {
                    $display = utf8_decode($display);
                }
            }
            $result[$pin_uid] = [
                'id' => $objParticipant->id,
                'count' => $count,
                'date' => date('Y-m-d H:i:s', $objParticipant->tstamp),
                'lastpage' => $objParticipant->lastpage,
                'finished' => $objParticipant->finished,
                'display' => $display,
            ];
        }

        return $result;
    }

    /**
     * Exports some basic information in the unused top left area.
     *
     * @TODO: Quick and dirty implementation for the alpha version, make translatable / better.
     *
     * @param mixed $sheet
     */
    protected function exportTopLeftArea(& $exporter, $sheet)
    {
        $result = [];

        // Legends for the question headers
        $row = 0;
        $col = 4;

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_id'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_nr'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_pg_nr'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_type'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_answered'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_skipped'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row++, $col, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_title'].':',
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        // Legends for the participant headers
        $col = 0;
        $exporter->setCellValue($sheet, $row, $col++, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_id_gen'],
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::TEXTWRAP => true,
            Exporter::COLWIDTH => 6 * 256,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row, $col++, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_sort'],
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::TEXTWRAP => true,
            Exporter::COLWIDTH => 5 * 256,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row, $col++, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_date'],
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::TEXTWRAP => true,
            Exporter::COLWIDTH => 14 * 256,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row, $col++, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_lastpage'],
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::TEXTWRAP => true,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        $exporter->setCellValue($sheet, $row, $col++, [
            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_participant'],
            Exporter::BGCOLOR => '#C0C0C0',
            Exporter::COLOR => '#000000',
            Exporter::TEXTWRAP => true,
            Exporter::COLWIDTH => 14 * 256,
            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
            Exporter::ALIGNMENT => Exporter::ALIGNMENT_H_RIGHT,
        ]);

        return $result;
    }

    /**
     * Exports base/identifying information for all participants.
     *
     * Every participant has it's own row with several header columns.
     *
     * @param mixed $sheet
     * @param mixed $participants
     */
    protected function exportParticipantRowHeaders(& $exporter, $sheet, & $rowCounter, & $colCounter, $participants)
    {
        $result = [];
        $row = $rowCounter;

        foreach ($participants as $key => $participant) {
            $col = $colCounter;

            foreach ($participant as $k => $v) {
                if ('finished' === $k) {
                    continue;
                }
                $cell = [
                    Exporter::DATA => $v,
                    Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO,
                ];

                switch ($k) {
                    case 'id':
                    case 'count':
                    case 'lastpage':
                        $cell[Exporter::CELLTYPE] = Exporter::CELLTYPE_FLOAT;
                        break;

                    case 'display':
                        if ($participant['finished']) {
                            $cell[Exporter::FONTWEIGHT] = Exporter::FONTWEIGHT_BOLD;
                        }

                        // no break
                    default:
                        break;
                }
                $exporter->setCellValue($sheet, $row, $col++, $cell);
            }
            ++$row;
        }
        $rowCounter = $row;
        $colCounter = $col;

        return $result;
    }
}
