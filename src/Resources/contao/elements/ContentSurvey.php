<?php

/**
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 * @package    survey_ce
 * @license    LGPL
 * @filesource
 */

namespace Hschottm\SurveyBundle;

use Hschottm\SurveyBundle\SurveyModel;

class ContentSurvey extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_survey';
	protected $objSurvey = null;
	protected $questionblock_template = 'survey_questionblock';
	protected $pin;
	private $questionpositions;


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### SURVEY ###';

			return $objTemplate->parse();
		}

		$this->strTemplate = (strlen($this->surveyTpl)) ? $this->surveyTpl : $this->strTemplate;
		return parent::generate();
	}

  /**
	 * Generate module
	 */
	protected function compile()
	{
		if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && ($this->invisible || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time())))
		{
			return '';
		}

		// Get front end user object
		$this->import('FrontendUser', 'User');

		// add survey javascript
		if (is_array($GLOBALS['TL_JAVASCRIPT']))
		{
			array_insert($GLOBALS['TL_JAVASCRIPT'], 1, 'bundles/hschottmsurvey/js/survey.js');
		}
		else
		{
			$GLOBALS['TL_JAVASCRIPT'] = array('bundles/hschottmsurvey/js/survey.js');
		}

		$surveyID = (strlen(\Input::post('survey'))) ? \Input::post('survey') : $this->survey;

    $this->objSurvey = $this->Database->prepare("SELECT * FROM tl_survey WHERE id=?")
			->execute($surveyID);
    $this->objSurvey->next();
		//$this->objSurvey = \Hschottm\SurveyBundle\SurveyModel::findByPk($surveyId);
    if (null === $this->objSurvey) {
        return;
    }

		$this->import('\Hschottm\SurveyBundle\Survey', 'svy');

		// check date activation
		if ((strlen($this->objSurvey->online_start)) && ($this->objSurvey->online_start > time())) {
			$this->Template->protected = true;
			return;
		}
		if ((strlen($this->objSurvey->online_end)) && ($this->objSurvey->online_end < time())) {
			$this->Template->protected = true;
			return;
		}

    $pages = \Hschottm\SurveyBundle\SurveyPageModel::findBy('pid', $surveyId, ['order' => 'sorting']);
    if (null === $pages) {
        $pages = [];
    } else {
        $pages = $pages->fetchAll();
    }

		$page = (\Input::post('page')) ? \Input::post('page') : 0;
		// introduction page / status
		if ($page == 0)
		{
			$this->outIntroductionPage();
		}

		// check survey start
		if (\Input::post('start') || ($this->objSurvey->immediate_start == 1 && !\Input::post('FORM_SUBMIT')))
		{
			$page = 0;
			switch ($this->objSurvey->access)
			{
				case 'anon':
					if (($this->objSurvey->usecookie) && strlen($_COOKIE['TLsvy_' . $this->objSurvey->id]) && $this->svy->checkPINTAN($this->objSurvey->id, $_COOKIE['TLsvy_' . $this->objSurvey->id]) !== false)
					{
						$page = $this->svy->getLastPageForPIN($this->objSurvey->id, $_COOKIE['TLsvy_' . $this->objSurvey->id]);
						$this->pin = $_COOKIE['TLsvy_' . $this->objSurvey->id];
					}
					else
					{
						$pintan = $this->svy->generatePIN_TAN();
						if ($this->objSurvey->usecookie) setcookie('TLsvy_' . $this->objSurvey->id, $pintan["PIN"], time()+3600*24*365, "/");
						$this->pin = $pintan["PIN"];
            $this->insertPinTan($this->objSurvey->id, $pintan["PIN"], $pintan["TAN"], 1);
						$this->insertParticipant($this->objSurvey->id, $pintan["PIN"]);
						$page = 1;
					}
					break;
				case 'anoncode':
					$tan = \Input::post('tan');
					if ((strcmp(\Input::post('FORM_SUBMIT'), 'tl_survey_form') == 0) && (strlen($tan)))
					{
						$result = $this->svy->checkPINTAN($this->objSurvey->id, "", $tan);
						if ($result === false)
						{
							$this->Template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_wrong_tan'];
						}
						else
						{
							$this->pin = $this->svy->getPINforTAN($this->objSurvey->id, $tan);
							if ($result == 0)
							{
                $res = \Hschottm\SurveyBundle\SurveyPinTanModel::findOneBy(['tan=?', 'pid=?'], [$tan, $this->objSurvey->id]);
                if (null != $res)
                {
                  $res->used = 1;
                  $res->save();
                }
								// set pin
								if ($this->objSurvey->usecookie) setcookie('TLsvy_' . $this->objSurvey->id, $this->pin, time()+3600*24*365, "/");
								$this->insertParticipant($this->objSurvey->id, $this->pin);
								$page = 1;
							}
							else
							{
								$status = $this->svy->getSurveyStatus($this->objSurvey->id, $this->pin);
								if (strcmp($status, "finished") == 0)
								{
									$this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
									$this->Template->hideStartButtons = true;
								}
								else
								{
									$page = $this->svy->getLastPageForPIN($this->objSurvey->id, $this->pin);
								}
							}
						}
					}
					else
					{
						$this->Template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_please_enter_tan'];
					}
					break;
				case 'nonanoncode':
          $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::fetchOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->User->id]);
          if (null != $participant)
          {
            if (!$participant->uid)
  					{
  						$pintan = $this->svy->generatePIN_TAN();
  						$this->pin = $pintan["PIN"];
  						$this->insertParticipant($this->objSurvey->id, $pintan["PIN"], $this->User->id);
  					}
  					else
  					{
  						$this->pin = $participant->pin;
  					}
  					$page = strlen($participant->lastpage) ? $participant->lastpage : 1;
          }
					break;
			}
		}
		// check question input and save input or return a question list of the page
		$surveypage = array();
		if (($page > 0 && $page <= count($pages)))
		{
			if (\Input::post('FORM_SUBMIT') == 'tl_survey')
			{
				$goback = (strlen(\Input::post("prev"))) ? true : false;
				$surveypage = $this->createSurveyPage($pages[$page-1], $page, true, $goback);
			}
		}

		// submit successful, calculate next page and return a question list of the new page
		if (count($surveypage) == 0)
		{
			if (strlen(\Input::post("next"))) $page++;
			if (strlen(\Input::post("finish"))) $page++;
			if (strlen(\Input::post("prev"))) $page--;

			$surveypage = $this->createSurveyPage($pages[$page-1], $page, false);
		}

		// save position of last page (for resume)
		if ($page > 0)
		{
      $res = \Hschottm\SurveyBundle\SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
      if (null != $res)
      {
        $res->lastpage = $page;
        $res->save();
      }
			if (strlen($pages[$page-1]['page_template'])) $this->questionblock_template = $pages[$page-1]['page_template'];
		}
		$questionBlockTemplate = new \FrontEndTemplate($this->questionblock_template);
		$questionBlockTemplate->surveypage = $surveypage;

		// template output
		$this->Template->pages = $pages;
		$this->Template->survey_id = $this->objSurvey->id;
		$this->Template->show_title = $this->objSurvey->show_title;
		$this->Template->show_cancel = ($page > 0 && count($surveypage)) ? $this->objSurvey->show_cancel : false;
		$this->Template->surveytitle = specialchars($this->objSurvey->title);
		$this->Template->cancel = specialchars($GLOBALS['TL_LANG']['MSC']['cancel_survey']);
		global $objPage;
		$this->Template->cancellink = $this->generateFrontendUrl($objPage->row());
		$this->Template->allowback = $this->objSurvey->allowback;
		$this->Template->questionblock = $questionBlockTemplate->parse();
		$this->Template->page = $page;
		$this->Template->introduction = $this->objSurvey->introduction;
		$this->Template->finalsubmission = ($this->objSurvey->finalsubmission) ? $this->objSurvey->finalsubmission : $GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'];
		$formaction = \Environment::get('request');

		$this->Template->pageXofY = $GLOBALS['TL_LANG']['MSC']['page_x_of_y'];
		$this->Template->next = $GLOBALS['TL_LANG']['MSC']['survey_next'];
		$this->Template->prev = $GLOBALS['TL_LANG']['MSC']['survey_prev'];
		$this->Template->start = $GLOBALS['TL_LANG']['MSC']['survey_start'];
		$this->Template->finish = $GLOBALS['TL_LANG']['MSC']['survey_finish'];
		$this->Template->pin = $this->pin;
		$this->Template->action = ampersand($formaction);
	}

	/**
	 * Create an array of widgets containing the questions on a given survey page
	 *
	 * @param array
	 * @param boolean
	 */
	protected function createSurveyPage($pagerow, $pagenumber, $validate = true, $goback = false)
	{
		$this->questionpositions = array();
		if (!strlen($this->pin)) $this->pin = \Input::post('pin');
		$surveypage = array();
		$pagequestioncounter = 1;
		$doNotSubmit = false;

    $questions = \Hschottm\SurveyBundle\SurveyQuestionModel::findBy('pid', $pagerow['id'], ['order' => 'sorting']);

    if (null === $questions) {
        return [];
    }

		foreach ($questions as $question)
		{
			$strClass = $GLOBALS['TL_SVY'][$question['questiontype']];
			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$objWidget = new $strClass();
			$objWidget->surveydata = $question;
			$objWidget->absoluteNumber = $this->getQuestionPosition($question['id'], $this->objSurvey->id);
			$objWidget->pageQuestionNumber = $pagequestioncounter;
			$objWidget->pageNumber = $pagenumber;
			$objWidget->cssClass = ($question['cssClass'] != '' ? ' ' . $question['cssClass'] : '') . ($objWidget->absoluteNumber%2 == 0 ? ' odd' : ' even');
			array_push($surveypage, $objWidget);
			$pagequestioncounter++;

			if ($validate)
			{
				$objWidget->validate();
				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			} else {
				// load existing values
				switch ($this->objSurvey->access)
				{
					case 'anon':
					case 'anoncode':
            $objResult == \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'pin=?'], [$this->objSurvey->id, $objWidget->id, $this->pin]);
						break;
					case 'nonanoncode':
            $objResult = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'uid=?'], [$this->objSurvey->id, $objWidget->id, $this->User->id]);
						break;
				}
				if ($objResult->numRows)
				{
					$objWidget->value = deserialize($objResult->result);
				}
			}
		}
		if ($validate)
		{
			// HOOK: pass validated questions to callback functions
			if (isset($GLOBALS['TL_HOOKS']['surveyQuestionsValidated']) && is_array($GLOBALS['TL_HOOKS']['surveyQuestionsValidated']))
			{
				foreach ($GLOBALS['TL_HOOKS']['surveyQuestionsValidated'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($surveypage, $pagerow);
				}
			}
		}
		else
		{
			// HOOK: pass loaded questions to callback functions
			if (isset($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded']) && is_array($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded']))
			{
				foreach ($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($surveypage, $pagerow);
				}
			}
		}

		if ($validate && \Input::post('FORM_SUBMIT') == 'tl_survey' && !strlen($this->pin))
		{
			if ($this->objSurvey->usecookie && strlen($_COOKIE['TLsvy_' . $this->objSurvey->id]))
			{
				// restore lost PIN from cookie
				$this->pin = $_COOKIE['TLsvy_' . $this->objSurvey->id];
			}
			else
			{
				// PIN got lost, restart
				global $objPage;
				$this->redirect($this->generateFrontendUrl($objPage->row()));
			}
		}

		// save survey values
		if ($validate && \Input::post('FORM_SUBMIT') == 'tl_survey' && (!$doNotSubmit || $goback))
		{
			if (!strlen($this->pin) || !$this->isValid($this->pin))
			{
				global $objPage;
				$this->redirect($this->generateFrontendUrl($objPage->row()));
			}
			foreach ($surveypage as $question)
			{
				switch ($this->objSurvey->access)
				{
					case 'anon':
					case 'anoncode':
            $res = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?','qid=?','pin=?'],[$this->objSurvey->id, $question->id, $this->pin]);
            if (null != $res)
            {
              if ($res instanceof Model)
              {
                $res->delete();
              } else if ($res instanceof Model\Collection) {
                foreach ($res as $singleRes)
                {
                  $singleRes->delete();
                }
              }
            }
						$value = $question->value;
						if (is_array($question->value))
						{
							$value = serialize($question->value);
						}
						if (strlen($value))
						{
              $this->insertResult($this->objSurvey->id, $question->id, $this->pin, $value);
						}
						break;
					case 'nonanoncode':
              $res = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?','qid=?','uid=?'],[$this->objSurvey->id, $question->id, $this->User->id]);
              if (null != $res)
              {
                if ($res instanceof Model)
                {
                  $res->delete();
                } else if ($res instanceof Model\Collection) {
                  foreach ($res as $singleRes)
                  {
                    $singleRes->delete();
                  }
                }
              }
						$value = $question->value;
						if (is_array($question->value))
						{
							$value = serialize($question->value);
						}
						if (strlen($value))
						{
              $this->insertResult($this->objSurvey->id, $question->id, $this->pin, $value, $this->User->id);
						}
						break;
				}
			}
			if (\Input::post('finish'))
			{
				// finish the survey
				switch ($this->objSurvey->access)
				{
					case 'anon':
					case 'anoncode':
            $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::fetchOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
            $participant->finished = 1;
            $participant->save();
						break;
					case 'nonanoncode':
            $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::fetchOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->User->id]);
            $participant->finished = 1;
            $participant->save();
						break;
				}
				// HOOK: pass survey data to callback functions when survey is finished
				if (isset($GLOBALS['TL_HOOKS']['surveyFinished']) && is_array($GLOBALS['TL_HOOKS']['surveyFinished']))
				{
					foreach ($GLOBALS['TL_HOOKS']['surveyFinished'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->objSurvey->row());
					}
				}
				if ($this->objSurvey->jumpto)
				{
          $pagedata = \PageModel::findByPk($this->objSurvey->jumpto);
          if (null !== $pagedata)
          {
            $this->redirect($pagedata->getFrontendUrl());
          }
				}
			}
		}
		return (($doNotSubmit || !$validate) && !$goback) ? $surveypage : array();
	}

	protected function getQuestionPosition($question_id, $survey_id)
	{
		if ($question_id > 0 && $survey_id > 0)
		{
			if (!count($this->questionpositions))
			{
				$execute = (method_exists($this->Database, 'executeUncached')) ? 'executeUncached' : 'execute';
				$this->questionpositions = $this->Database->prepare("SELECT tl_survey_question.id FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting")
					->$execute($survey_id)
					->fetchEach('id');
			}
			return array_search($question_id, $this->questionpositions) + 1;
		}
		else
		{
			return 0;
		}
	}

/**
 * Check if the active participant is still valid (maybe participant data was deleted by the survey administrator)
 *
 * @return boolean
 **/
	protected function isValid($pin)
	{
		if (strlen($pin) == 0) return false;
    $participants = \Hschottm\SurveyBundle\SurveyParticipantModel::findBy(['pin=?', 'pid=?'], [$pin, $this->objSurvey->id]);
    if (null === $participants) {
        return false;
    }
    if (1 === $participants->count()) {
        return true;
    }
    return false;
	}

	protected function outIntroductionPage()
	{
		switch ($this->objSurvey->access)
		{
			case 'anon':
				$status = "";
				if ($this->objSurvey->usecookie)
				{
					$status = $this->svy->getSurveyStatus($this->objSurvey->id, $_COOKIE['TLsvy_' . $this->objSurvey->id]);
				}
				if (strcmp($status, "finished") == 0)
				{
					$this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
					$this->Template->hideStartButtons = true;
				}
				break;
			case 'anoncode':
				$this->loadLanguageFile("tl_content");
				$this->Template->needsTAN = true;
				$this->Template->txtTANInputDesc = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start_desc'];
				$this->Template->txtTANInput = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start'];
				if (strlen(\Input::get('code')))
				{
					$this->Template->tancode = \Input::get('code');
				}
				break;
			case 'nonanoncode':
				if (!$this->User->id)
				{
					$this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_member'];
					$this->Template->hideStartButtons = true;
				}
				else if ($this->objSurvey->limit_groups)
				{
					if (!$this->svy->isUserAllowedToTakeSurvey($this->objSurvey))
					{
						$this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_allowed_member'];
						$this->Template->hideStartButtons = true;
					}
				}
				else
				{
					$status = $this->svy->getSurveyStatusForMember($this->objSurvey->id, $this->User->id);
					if (strcmp($status, "finished") == 0)
					{
						$this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
						$this->Template->hideStartButtons = true;
					}
				}
				break;
		}
	}

  protected function insertResult($pid, $qid, $pin, $result, $uid = null)
  {
    $newResult         = new \Hschottm\SurveyBundle\SurveyResultModel();
    $newResult->tstamp = time();
    $newResult->pid    = $pid;
    $newResult->qid    = $qid;
    $newResult->pin    = $pin;
    $newResult->result   = $result;
    if (null != $uid)
    {
      $newResult->uid = $uid;
    }
    $newResult->save();
  }

  protected function insertPinTan($pid, $pin, $tan, $used)
  {
    $newParticipant         = new \Hschottm\SurveyBundle\SurveyPinTanModel();
    $newParticipant->tstamp = time();
    $newParticipant->pid    = $pid;
    $newParticipant->pin    = $pin;
    $newParticipant->tan    = $tan;
    $newParticipant->used   = $used;
    $newParticipant->save();
  }

	/**
	 * Insert a new participant dataset
	 */
	protected function insertParticipant($pid, $pin, $uid = 0)
	{
    $newParticipant         = new \Hschottm\SurveyBundle\SurveyParticipantModel();
    $newParticipant->tstamp = time();
    $newParticipant->pid    = $pid;
    $newParticipant->pin    = $pin;
    $newParticipant->uid    = $uid;
    $newParticipant->save();
	}
}

?>
