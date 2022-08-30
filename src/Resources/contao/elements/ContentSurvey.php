<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\Database\Result;
use Contao\Email;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Model\Collection;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Validator;
use Hschottm\SurveyBundle\DataContainer\SurveyPageContainer;

/**
 * @property SurveyModel|Result $objSurvey
 */
class ContentSurvey extends ContentElement
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_survey';
    protected $surveyModel;
    protected $questionblock_template = 'survey_questionblock';
    protected $pin;
    private $questionpositions;

    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### SURVEY ###';

            return $objTemplate->parse();
        }

        $this->strTemplate = (\strlen($this->surveyTpl)) ? $this->surveyTpl : $this->strTemplate;

        return parent::generate();
    }

    /**
     * Generate module.
     */
    protected function compile()
    {
        if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && ($this->invisible || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time()))) {
            return '';
        }

        // Get front end user object
        $this->import('FrontendUser', 'User');

        // add survey javascript
        if (\is_array($GLOBALS['TL_JAVASCRIPT'])) {
            array_insert($GLOBALS['TL_JAVASCRIPT'], 1, 'bundles/hschottmsurvey/js/survey.js');
        } else {
            $GLOBALS['TL_JAVASCRIPT'] = ['bundles/hschottmsurvey/js/survey.js'];
        }

        $surveyID = (\strlen(Input::post('survey'))) ? Input::post('survey') : $this->survey;

        $this->objSurvey = $this->Database->prepare('SELECT * FROM tl_survey WHERE id=?')
            ->execute($surveyID);
        $this->objSurvey->next();
        //$this->objSurvey = \Hschottm\SurveyBundle\SurveyModel::findByPk($surveyId);
        if (null == $this->objSurvey) {
            return;
        }

        $this->import('\Hschottm\SurveyBundle\Survey', 'svy');

        // check date activation
        if ((\strlen($this->objSurvey->online_start)) && ($this->objSurvey->online_start > time())) {
            $this->Template->protected = true;

            return;
        }
        if ((\strlen($this->objSurvey->online_end)) && ($this->objSurvey->online_end < time())) {
            $this->Template->protected = true;

            return;
        }

        $pages = \Hschottm\SurveyBundle\SurveyPageModel::findBy('pid', $surveyID, ['order' => 'sorting']);

        if (null == $pages) {
            $pages = [];
        } else {
            $pages = $pages->fetchAll();
        }

        $page = (Input::post('page')) ? Input::post('page') : 0;
        // introduction page / status
        if (0 == $page) {
            $this->outIntroductionPage();
        }
        // check survey start
        if (Input::post('start') || (1 == $this->objSurvey->immediate_start && !Input::post('FORM_SUBMIT'))) {
            $page = 0;
            switch ($this->objSurvey->access) {
                case 'anon':
                    if (($this->objSurvey->usecookie) && \strlen($_COOKIE['TLsvy_'.$this->objSurvey->id]) && false != $this->svy->checkPINTAN($this->objSurvey->id, $_COOKIE['TLsvy_'.$this->objSurvey->id])) {
                        $page = $this->svy->getLastPageForPIN($this->objSurvey->id, $_COOKIE['TLsvy_'.$this->objSurvey->id]);
                        $this->pin = $_COOKIE['TLsvy_'.$this->objSurvey->id];
                    } else {
                        $pintan = $this->svy->generatePIN_TAN();
                        if ($this->objSurvey->usecookie) {
                            setcookie('TLsvy_'.$this->objSurvey->id, $pintan['PIN'], time() + 3600 * 24 * 365, '/');
                        }
                        $this->pin = $pintan['PIN'];
                        $this->insertPinTan($this->objSurvey->id, $pintan['PIN'], $pintan['TAN'], 1);
                        $this->insertParticipant($this->objSurvey->id, $pintan['PIN']);
                        $page = 1;
                    }
                    break;
                case 'anoncode':
                    $tan = Input::post('tan');
                    if ((0 == strcmp(Input::post('FORM_SUBMIT'), 'tl_survey_form')) && (\strlen($tan))) {
                        $result = $this->svy->checkPINTAN($this->objSurvey->id, '', $tan);
                        if (false === $result) {
                            $this->Template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_wrong_tan'];
                        } else {
                            $this->pin = $this->svy->getPINforTAN($this->objSurvey->id, $tan);

                            if (0 == $result) {
                                $res = \Hschottm\SurveyBundle\SurveyPinTanModel::findOneBy(['tan=?', 'pid=?'], [$tan, $this->objSurvey->id]);
                                if (null != $res) {
                                    $res->used = 1;
                                    $res->save();
                                }
                                // set pin
                                if ($this->objSurvey->usecookie) {
                                    setcookie('TLsvy_'.$this->objSurvey->id, $this->pin, time() + 3600 * 24 * 365, '/');
                                }
                                $this->insertParticipant($this->objSurvey->id, $this->pin);
                                $page = 1;
                            } else {
                                $status = $this->svy->getSurveyStatus($this->objSurvey->id, $this->pin);
                                if (0 == strcmp($status, 'finished')) {
                                    $this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                                    $this->Template->hideStartButtons = true;
                                } else {
                                    $page = $this->svy->getLastPageForPIN($this->objSurvey->id, $this->pin);
                                }
                            }
                        }
                    } else {
                        $this->Template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_please_enter_tan'];
                    }
                    break;
                case 'nonanoncode':
                  $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::findOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->User->id]);
                  if (null == $participant) {
                    $pintan = $this->svy->generatePIN_TAN();
                    $this->pin = $pintan['PIN'];
                    $this->insertParticipant($this->objSurvey->id, $pintan['PIN'], $this->User->id);
                  }
                  else {
                    $this->pin = $participant->pin;
                  }
                  $page = \strlen($participant->lastpage) ? $participant->lastpage : 1;
                  break;
            }
        }
        // check question input and save input or return a question list of the page
        $surveypage = [];
        if (($page > 0 && $page <= \count($pages))) {
            if ('tl_survey' == Input::post('FORM_SUBMIT')) {
                $goback = (\strlen(Input::post('prev'))) ? true : false;
                $surveypage = $this->createSurveyPage($pages[$page - 1], $page, true, $goback);
            }
        }

        // submit successful, calculate next page and return a question list of the new page
        $previouspage = $page;
        if (0 == \count($surveypage)) {
            if (\strlen(Input::post('next'))) {
                $pageid = $this->evaluateConditions($pages[$page-1]);
                if (null == $pageid)
                {
                  $page++;
                }
                else
                {
                  foreach ($pages as $idx => $p)
                  {
                    if ($p['id'] == $pageid)
                    {
                      $page = $idx + 1;
                    }
                  }
                }
                $this->insertNavigation($this->objSurvey->id, $this->pin, $this->User->id, $previouspage, $page);
            }
            if (\strlen(Input::post('finish'))) {
                $page++;
            }
            if (\strlen(Input::post('prev'))) {
                $res = \Hschottm\SurveyBundle\SurveyNavigationModel::findOneBy(['pid=?', 'pin=?', 'uid=?', 'topage=?'], [$this->objSurvey->id, $this->pin, (strlen($this->User->id) == 0) ? 0 : $this->User->id, $page], ['order' => 'tstamp DESC']);
                if (null != $res) {
                  $page = $res->frompage;
                }
                else {
                  $page--;
                }
            }

            $surveypage = $this->createSurveyPage($pages[$page - 1], $page, false);
        }

        // save position of last page (for resume)
        if ($page > 0) {
            $res = \Hschottm\SurveyBundle\SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
            if (null != $res) {
                $res->lastpage = $page;
                $res->save();
            }
            if (\strlen($pages[$page - 1]['page_template'])) {
                $this->questionblock_template = $pages[$page - 1]['page_template'];
            }
        }

        if ('result' === ($pages[$page - 1]['type'] ?? 'default')) {
            $this->createResultPage($pages[$page - 1]);
        } else {
            $questionBlockTemplate             = new FrontendTemplate($this->questionblock_template);
            $questionBlockTemplate->surveypage = $surveypage;
            if (is_array($pages)) {
                $helper = new SurveyHelper();

                foreach ($pages as $pageidx => $pagerow) {
                    $replacements            = [];
                    $pagerow['introduction'] = $helper->replaceTags($pagerow['introduction'], $this->pin, $replacements, true);
                    $pages[$pageidx]         = $pagerow;
                }
            }

            $qb = $questionBlockTemplate->parse();
            $replacements = [];
            $qb = $helper->replaceTags($qb, $this->pin, $replacements, true);
            $this->Template->questionblock = $qb;
        }

        if (SurveyPageContainer::PAGETYPE_RESULT === ($pages[$page]['type'] ?? 'default') && $pages[$page]['useCustomNextButtonTitle'] ?? false) {
            $this->Template->next = $pages[$page]['customNextButtonTitle'];
        } else {
            $this->Template->next = $GLOBALS['TL_LANG']['MSC']['survey_next'];
        }

        if ($this->objSurvey->allowback && (SurveyPageContainer::PAGETYPE_RESULT === ($pages[$page - 1]['type'] ?? 'default')) && $pages[$page - 1]['hideBackButton'] ?? false) {
            $this->Template->allowback = false;
        } else {
            $this->Template->allowback = $this->objSurvey->allowback;
        }
        
        // template output
        $this->Template->pages = $pages;
        $this->Template->survey_id = $this->objSurvey->id;
        $this->Template->show_title = $this->objSurvey->show_title;
        $this->Template->show_cancel = ($page > 0 && \count($surveypage)) ? $this->objSurvey->show_cancel : false;
        $this->Template->surveytitle = StringUtil::specialchars($this->objSurvey->title);
        $this->Template->cancel = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['cancel_survey']);
        global $objPage;
        $this->Template->cancellink = $this->generateFrontendUrl($objPage->row());

	$this->Template->page = $page;
        $this->Template->introduction = $this->objSurvey->introduction;
        $this->Template->finalsubmission = ($this->objSurvey->finalsubmission) ? $this->objSurvey->finalsubmission : $GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'];
        $formaction = Environment::get('request');

        $this->Template->pageXofY = $GLOBALS['TL_LANG']['MSC']['page_x_of_y'];
        $this->Template->prev = $GLOBALS['TL_LANG']['MSC']['survey_prev'];
        $this->Template->start = $GLOBALS['TL_LANG']['MSC']['survey_start'];
        $this->Template->finish = $GLOBALS['TL_LANG']['MSC']['survey_finish'];
        $this->Template->pin = $this->pin;
        $this->Template->action = ampersand($formaction);
    }

    protected function evaluateConditions($page)
    {
      $conditions = [];
      $conditionModel = \Hschottm\SurveyBundle\SurveyConditionModel::findBy(['pid=?'], [$page['id']]);
      if (null != $conditionModel) {
        $conditions = $conditionModel->fetchAll();
      }
      $groups = [];
      foreach ($conditions as $condition)
      {
        $groups[$condition['grp']][] = $condition;
      }
      foreach ($groups as $group)
      {
        $applies = true;
        foreach ($group as $condition)
        {
          if ($condition['qid'] == 0)
          {
            return $condition['pageid'];
          }
          else
          {
            $res = $this->getResultForQuestion($condition['qid']);
            $questionModel = \Hschottm\SurveyBundle\SurveyQuestionModel::findOneBy('id', $condition['qid']);
            if (null != $res)
            {
              // check if condition is valid
              if ($condition['relation'] == '=')
              {
		if ($questionModel->questiontype == 'multiplechoice')
		{
			if (is_array($res['value']))
			{
				$applies = $applies && in_array($condition['condition'], $res['value']);
			}
			else
			{
		                $applies = $applies && ($res['value'] == $condition['condition']);
			}
		}
		else
		{
	                $applies = $applies && ($res['value'] == $condition['condition']);
		}
              } else if ($condition['relation'] == '>') {
                $applies = $applies && ($res['value'] > $condition['condition']);
              } else if ($condition['relation'] == '<') {
                $applies = $applies && ($res['value'] < $condition['condition']);
              } else if ($condition['relation'] == '<=') {
                $applies = $applies && ($res['value'] <= $condition['condition']);
              } else if ($condition['relation'] == '>=') {
                $applies = $applies && ($res['value'] >= $condition['condition']);
              } else if ($condition['relation'] == '!=') {
                $applies = $applies && ($res['value'] != $condition['condition']);
              }
            }
          }
        }
        if ($applies)
        {
          $condition = array_shift($group);
          return $condition['pageid'];
        }
      }
      return null;
    }

    protected function getResultForQuestion($question_id)
    {
      $objResult = $this->Database->prepare("SELECT * FROM tl_survey_result WHERE (pid=? AND qid=? AND pin=?)")
                      ->execute($this->objSurvey->id, $question_id, $this->pin);
      if ($objResult->numRows)
      {
              return deserialize($objResult->result);
      }
      else
      {
              return null;
      }
    }

    /**
     * Create an array of widgets containing the questions on a given survey page.
     *
     * @param array
     * @param bool
     * @param mixed $pagerow
     * @param mixed $pagenumber
     * @param mixed $validate
     * @param mixed $goback
     */
    protected function createSurveyPage($pagerow, $pagenumber, $validate = true, $goback = false)
    {
        $this->questionpositions = [];
        if (!\strlen($this->pin)) {
            $this->pin = Input::post('pin');
        }
        $surveypage = [];
        $pagequestioncounter = 1;
        $doNotSubmit = false;

        $questions = \Hschottm\SurveyBundle\SurveyQuestionModel::findBy('pid', $pagerow['id'], ['order' => 'sorting']);

        if (null == $questions) {
            $questions = [];
        }

        foreach ($questions as $questionModel) {
            $question = $questionModel->row();
            $strClass = $GLOBALS['TL_SVY'][$question['questiontype']];
            // Continue if the class is not defined
            if (!$this->classFileExists($strClass)) {
                continue;
            }

            $objWidget = new $strClass();
            $objWidget->surveydata = $question;
            $objWidget->absoluteNumber = $this->getQuestionPosition($question['id'], $this->objSurvey->id);
            $objWidget->pageQuestionNumber = $pagequestioncounter;
            $objWidget->pageNumber = $pagenumber;
            $objWidget->cssClass = ('' != $question['cssClass'] ? ' '.$question['cssClass'] : '').(0 == $objWidget->absoluteNumber % 2 ? ' odd' : ' even');
            array_push($surveypage, $objWidget);
            ++$pagequestioncounter;

            if ($validate) {
                $objWidget->validate();
                if ($objWidget->hasErrors()) {
                    $doNotSubmit = true;
                }
            } else {
                // load existing values
                switch ($this->objSurvey->access) {
                    case 'anon':
                    case 'anoncode':
                        $objResult = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'pin=?'], [$this->objSurvey->id, $objWidget->id, $this->pin]);
                        break;
                    case 'nonanoncode':
                        $objResult = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'uid=?'], [$this->objSurvey->id, $objWidget->id, $this->User->id]);
                        break;
                }
                if (null != $objResult && $objResult->count()) {
                    $objWidget->value = StringUtil::deserialize($objResult->result);
                }
            }
        }
        if ($validate) {
            // HOOK: pass validated questions to callback functions
            if (isset($GLOBALS['TL_HOOKS']['surveyQuestionsValidated']) && \is_array($GLOBALS['TL_HOOKS']['surveyQuestionsValidated'])) {
                foreach ($GLOBALS['TL_HOOKS']['surveyQuestionsValidated'] as $callback) {
                    $this->import($callback[0]);
                    $this->$callback[0]->$callback[1]($surveypage, $pagerow);
                }
            }
        } else {
            // HOOK: pass loaded questions to callback functions
            if (isset($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded']) && \is_array($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded'])) {
                foreach ($GLOBALS['TL_HOOKS']['surveyQuestionsLoaded'] as $callback) {
                    $this->import($callback[0]);
                    $this->$callback[0]->$callback[1]($surveypage, $pagerow);
                }
            }
        }

        if ($validate && 'tl_survey' == Input::post('FORM_SUBMIT') && !\strlen($this->pin)) {
            if ($this->objSurvey->usecookie && \strlen($_COOKIE['TLsvy_'.$this->objSurvey->id])) {
                // restore lost PIN from cookie
                $this->pin = $_COOKIE['TLsvy_'.$this->objSurvey->id];
            } else {
                // PIN got lost, restart
                global $objPage;
                $this->redirect($this->generateFrontendUrl($objPage->row()));
            }
        }

        // save survey values
        if ($validate && 'tl_survey' == Input::post('FORM_SUBMIT') && (!$doNotSubmit || $goback)) {
            if (!\strlen($this->pin) || !$this->isValid($this->pin)) {
                global $objPage;
                $this->redirect($this->generateFrontendUrl($objPage->row()));
            }
            foreach ($surveypage as $question) {
                switch ($this->objSurvey->access) {
                    case 'anon':
                    case 'anoncode':
                      $res = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'pin=?'], [$this->objSurvey->id, $question->id, $this->pin]);
                      if (null != $res) {
                        $modelinstance = "Model";
                        $collectioninstance = "Model\Collection";
                        if ($res instanceof $modelinstance) {
                          $res->delete();
                        } elseif ($res instanceof $collectioninstance) {
                          foreach ($res as $singleRes) {
                            $singleRes->delete();
                          }
                        }
                      }
                        $value = $question->value;
                        if (\is_array($question->value)) {
                            $value = serialize($question->value);
                        }
                        if (\strlen($value)) {
                            $this->insertResult($this->objSurvey->id, $question->id, $this->pin, $value);
                        }
                        break;
                    case 'nonanoncode':
                        $res = \Hschottm\SurveyBundle\SurveyResultModel::findBy(['pid=?', 'qid=?', 'uid=?'], [$this->objSurvey->id, $question->id, $this->User->id]);
                        $modelinstance = "Model";
                        $collectioninstance = "Model\Collection";
                        if (null != $res) {
                            if ($res instanceof $modelinstance) {
                                $res->delete();
                            } elseif ($res instanceof $collectioninstance) {
                                foreach ($res as $singleRes) {
                                    $singleRes->delete();
                                }
                            }
                        }
                        $value = $question->value;
                        if (\is_array($question->value)) {
                            $value = serialize($question->value);
                        }
                        if (\strlen($value)) {
                            $this->insertResult($this->objSurvey->id, $question->id, $this->pin, $value, $this->User->id);
                        }
                        break;
                }
            }

            if (Input::post('finish')) {
                // finish the survey
                switch ($this->objSurvey->access) {
                    case 'anon':
                    case 'anoncode':
            $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
            $participant->finished = 1;
            $participant->save();
                        break;
                    case 'nonanoncode':
            $participant = \Hschottm\SurveyBundle\SurveyParticipantModel::findOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->User->id]);
            $participant->finished = 1;
            $participant->save();
                        break;
                }
                // HOOK: pass survey data to callback functions when survey is finished
                if (isset($GLOBALS['TL_HOOKS']['surveyFinished']) && \is_array($GLOBALS['TL_HOOKS']['surveyFinished'])) {
                    foreach ($GLOBALS['TL_HOOKS']['surveyFinished'] as $callback) {
                        $this->import($callback[0]);
                        $this->{$callback[0]}->{$callback[1]}($this->objSurvey->row());
                    }
                }

                if ($this->objSurvey->sendConfirmationMail)
        				{
        					$objMailProperties = new \stdClass();
        					$objMailProperties->subject = '';
        					$objMailProperties->sender = '';
        					$objMailProperties->senderName = '';
        					$objMailProperties->replyTo = '';
        					$objMailProperties->recipients = array();
        					$objMailProperties->messageText = '';
        					$objMailProperties->messageHtml = '';
        					$objMailProperties->attachments = array();

        					// Set the sender as given in form configuration
        					[$senderName, $sender] = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailSender);
        					$objMailProperties->sender = $sender;
        					$objMailProperties->senderName = $senderName;

        					// Set the 'reply to' address, if given in form configuration
        					if (!empty($this->objSurvey->confirmationMailReplyto))
        					{
        						[$replyToName, $replyTo] = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailReplyto);
        						$objMailProperties->replyTo = (strlen($replyToName) ? $replyToName . ' <' . $replyTo . '>' : $replyTo);
        					}

        					// Set recipient(s)
        					if (strlen($this->objSurvey->confirmationMailRecipientField))
        					{
                    $res = \Hschottm\SurveyBundle\SurveyResultModel::findOneBy(['qid=?', 'pin=?'], [$this->objSurvey->confirmationMailRecipientField, $this->pin]);
                    if (null != $res) {
                      if (strlen($res->result))
          						{
          							$arrRecipient = trimsplit(',', $res->result);
          						}
                    }
        					}

        					if (!empty($this->objSurvey->confirmationMailRecipient))
        					{
        						$varRecipient = $this->objSurvey->confirmationMailRecipient;
        						$arrRecipient = array_merge($arrRecipient, trimsplit(',', $varRecipient));
        					}
        					$arrRecipient = array_filter(array_unique($arrRecipient));

        					if (!empty($arrRecipient))
        					{
        						foreach ($arrRecipient as $kR => $recipient)
        						{
        							[$recipientName, $recipient] = StringUtil::splitFriendlyEmail($this->replaceInsertTags($recipient, false));
        							$arrRecipient[$kR] = (strlen($recipientName) ? $recipientName . ' <' . $recipient . '>' : $recipient);
        						}
        					}
        					$objMailProperties->recipients = $arrRecipient;
        					// Check if we want custom attachments... (Thanks to Torben Schwellnus)
        					if ($this->objSurvey->addConfirmationMailAttachments)
        					{
        						if($this->objSurvey->confirmationMailAttachments)
        						{
        							$arrCustomAttachments = deserialize($this->objSurvey->confirmationMailAttachments, true);

        							if (!empty($arrCustomAttachments))
        							{
        								foreach ($arrCustomAttachments as $varFile)
        								{
        									$objFileModel = FilesModel::findById($varFile);

        									if ($objFileModel != null)
        									{
        										$objFile = new File($objFileModel->path);
        										if ($objFile->size)
        										{
        											$objMailProperties->attachments[TL_ROOT .'/' . $objFile->path] = array
        											(
        												'file' => TL_ROOT . '/' . $objFile->path,
        												'name' => $objFile->basename,
        												'mime' => $objFile->mime);
        										}
        									}
        								}
        							}
        						}
        					}

        					$objMailProperties->subject = StringUtil::decodeEntities($this->objSurvey->confirmationMailSubject);
        					$objMailProperties->messageText = StringUtil::decodeEntities($this->objSurvey->confirmationMailText);

        					$messageHtmlTmpl = '';
        					if (Validator::isUuid($this->objSurvey->confirmationMailTemplate) || (is_numeric($this->objSurvey->confirmationMailTemplate) && $this->objSurvey->confirmationMailTemplate > 0))
        					{
        						$objFileModel = FilesModel::findById($this->objSurvey->confirmationMailTemplate);
        						if ($objFileModel != null)
        						{
        							$messageHtmlTmpl = $objFileModel->path;
        						}
        					}
        					if ($messageHtmlTmpl != '')
        					{
        						$fileTemplate = new File($messageHtmlTmpl);
        						if ($fileTemplate->mime == 'text/html')
        						{
        							$messageHtml = $fileTemplate->getContent();
        							$objMailProperties->messageHtml = $messageHtml;
        						}
        					}
        					// Replace Insert tags and conditional tags
        					//$objMailProperties = $this->Formdata->prepareMailData($objMailProperties, $arrSubmitted, $arrFiles, $arrForm, $arrFormFields);

        					// Send Mail
        					$blnConfirmationSent = false;
        					if (!empty($objMailProperties->recipients))
        					{
        						$objMail = new Email();
        						$objMail->from = $objMailProperties->sender;

        						if (!empty($objMailProperties->senderName))
        						{
        							$objMail->fromName = $objMailProperties->senderName;
        						}

        						if (!empty($objMailProperties->replyTo))
        						{
        							$objMail->replyTo($objMailProperties->replyTo);
        						}

        						$helper = new SurveyHelper();

        						$objMail->subject = $objMailProperties->subject;

        						if (!empty($objMailProperties->attachments))
        						{
        							foreach ($objMailProperties->attachments as $strFile => $varParams)
        							{
        								$strContent = file_get_contents($varParams['file'], false);
        								$objMail->attachFileFromString($strContent, $varParams['name'], $varParams['mime']);
        							}
        						}

        						if (!empty($objMailProperties->messageText))
        						{
        							$objMail->text = $helper->replaceTags($objMailProperties->messageText, $this->pin, []);
        						}

        						if (!empty($objMailProperties->messageHtml))
        						{
        							$objMail->html = $helper->replaceTags($objMailProperties->messageHtml, $this->pin, [], true);
        						}

        						foreach ($objMailProperties->recipients as $recipient)
        						{
        							$objMail->sendTo($recipient);
        							$blnConfirmationSent = true;
        						}
        					}
        				}

                if ($this->objSurvey->sendConfirmationMailAlternate)
                {
                  $condition = true;
                  if ($this->objSurvey->confirmationMailAlternateCondition)
                  {
                    if ($helper->replaceTags(sprintf("{if %s}1{endif}", StringUtil::decodeEntities($this->objSurvey->confirmationMailAlternateCondition)), $this->pin, []) == '1')
                    {
                      $condition = true;
                    }
                    else {
                      $condition = false;
                    }
                  }
                  if ($condition)
                  {
                    $objMailProperties = new \stdClass();
          					$objMailProperties->subject = '';
          					$objMailProperties->sender = '';
          					$objMailProperties->senderName = '';
          					$objMailProperties->replyTo = '';
          					$objMailProperties->recipients = array();
          					$objMailProperties->messageText = '';
          					$objMailProperties->messageHtml = '';
          					$objMailProperties->attachments = array();

          					// Set the sender as given in form configuration
          					[$senderName, $sender] = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailAlternateSender);
          					$objMailProperties->sender = $sender;
          					$objMailProperties->senderName = $senderName;

          					// Set the 'reply to' address, if given in form configuration
          					if (!empty($this->objSurvey->confirmationMailAlternateReplyto))
          					{
          						[$replyToName, $replyTo] = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailAlternateReplyto);
          						$objMailProperties->replyTo = (strlen($replyToName) ? $replyToName . ' <' . $replyTo . '>' : $replyTo);
          					}

          					// Set recipient(s)
                    $arrRecipient = [];
          					if (!empty($this->objSurvey->confirmationMailAlternateRecipient))
          					{
          						$varRecipient = $this->objSurvey->confirmationMailAlternateRecipient;
          						$arrRecipient = array_merge($arrRecipient, trimsplit(',', $varRecipient));
          					}
          					$arrRecipient = array_filter(array_unique($arrRecipient));

          					if (!empty($arrRecipient))
          					{
          						foreach ($arrRecipient as $kR => $recipient)
          						{
          							[$recipientName, $recipient] = StringUtil::splitFriendlyEmail($this->replaceInsertTags($recipient, false));
          							$arrRecipient[$kR] = (strlen($recipientName) ? $recipientName . ' <' . $recipient . '>' : $recipient);
          						}
          					}
          					$objMailProperties->recipients = $arrRecipient;
          					// Check if we want custom attachments... (Thanks to Torben Schwellnus)
          					if ($this->objSurvey->addConfirmationMailAlternateAttachments)
          					{
          						if($this->objSurvey->confirmationMailAlternateAttachments)
          						{
          							$arrCustomAttachments = deserialize($this->objSurvey->confirmationMailAlternateAttachments, true);

          							if (!empty($arrCustomAttachments))
          							{
          								foreach ($arrCustomAttachments as $varFile)
          								{
          									$objFileModel = FilesModel::findById($varFile);

          									if ($objFileModel != null)
          									{
          										$objFile = new File($objFileModel->path);
          										if ($objFile->size)
          										{
          											$objMailProperties->attachments[TL_ROOT .'/' . $objFile->path] = array
          											(
          												'file' => TL_ROOT . '/' . $objFile->path,
          												'name' => $objFile->basename,
          												'mime' => $objFile->mime);
          										}
          									}
          								}
          							}
          						}
          					}

          					$objMailProperties->subject = StringUtil::decodeEntities($this->objSurvey->confirmationMailAlternateSubject);
          					$objMailProperties->messageText = StringUtil::decodeEntities($this->objSurvey->confirmationMailAlternateText);

          					$messageHtmlTmpl = '';
          					if (Validator::isUuid($this->objSurvey->confirmationMailAlternateTemplate) || (is_numeric($this->objSurvey->confirmationMailAlternateTemplate) && $this->objSurvey->confirmationMailAlternateTemplate > 0))
          					{
          						$objFileModel = FilesModel::findById($this->objSurvey->confirmationMailAlternateTemplate);
          						if ($objFileModel != null)
          						{
          							$messageHtmlTmpl = $objFileModel->path;
          						}
          					}
          					if ($messageHtmlTmpl != '')
          					{
          						$fileTemplate = new File($messageHtmlTmpl);
          						if ($fileTemplate->mime == 'text/html')
          						{
          							$messageHtml = $fileTemplate->getContent();
          							$objMailProperties->messageHtml = $messageHtml;
          						}
          					}
          					// Replace Insert tags and conditional tags
          					//$objMailProperties = $this->Formdata->prepareMailData($objMailProperties, $arrSubmitted, $arrFiles, $arrForm, $arrFormFields);

          					// Send Mail
          					$blnConfirmationSent = false;
          					if (!empty($objMailProperties->recipients))
          					{
          						$objMail = new Email();
          						$objMail->from = $objMailProperties->sender;

          						if (!empty($objMailProperties->senderName))
          						{
          							$objMail->fromName = $objMailProperties->senderName;
          						}

          						if (!empty($objMailProperties->replyTo))
          						{
          							$objMail->replyTo($objMailProperties->replyTo);
          						}

          						$helper = new SurveyHelper();

          						$objMail->subject = $objMailProperties->subject;

          						if (!empty($objMailProperties->attachments))
          						{
          							foreach ($objMailProperties->attachments as $strFile => $varParams)
          							{
          								$strContent = file_get_contents($varParams['file'], false);
          								$objMail->attachFileFromString($strContent, $varParams['name'], $varParams['mime']);
          							}
          						}

          						if (!empty($objMailProperties->messageText))
          						{
          							$objMail->text = $helper->replaceTags($objMailProperties->messageText, $this->pin, []);
          						}

          						if (!empty($objMailProperties->messageHtml))
          						{
          							$objMail->html = $helper->replaceTags($objMailProperties->messageHtml, $this->pin, [], true);
          						}

          						foreach ($objMailProperties->recipients as $recipient)
          						{
          							$objMail->sendTo($recipient);
          							$blnConfirmationSent = true;
          						}
          					}
                  }
                }

                if ($this->objSurvey->jumpto) {
                    $pagedata = PageModel::findByPk($this->objSurvey->jumpto);
                    if (null != $pagedata) {
                        $this->redirect($pagedata->getFrontendUrl());
                    }
                }
            }
        }

        return (($doNotSubmit || !$validate) && !$goback) ? $surveypage : [];
    }

    protected function getQuestionPosition($question_id, $survey_id)
    {
        if ($question_id > 0 && $survey_id > 0) {
            if (!\count($this->questionpositions)) {
                $execute = (method_exists($this->Database, 'executeUncached')) ? 'executeUncached' : 'execute';
                $this->questionpositions = $this->Database->prepare('SELECT tl_survey_question.id FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
                    ->$execute($survey_id)
                    ->fetchEach('id');
            }

            return array_search($question_id, $this->questionpositions, true) + 1;
        }

        return 0;
    }

    /**
     * Check if the active participant is still valid (maybe participant data was deleted by the survey administrator).
     *
     * @param mixed $pin
     *
     * @return bool
     **/
    protected function isValid($pin)
    {
        if (0 == \strlen($pin)) {
            return false;
        }
        $participants = \Hschottm\SurveyBundle\SurveyParticipantModel::findBy(['pin=?', 'pid=?'], [$pin, $this->objSurvey->id]);
        if (null == $participants) {
            return false;
        }
        if (1 == $participants->count()) {
            return true;
        }

        return false;
    }

    protected function outIntroductionPage()
    {
        switch ($this->objSurvey->access) {
            case 'anon':
                $status = '';
                if ($this->objSurvey->usecookie) {
                    $status = $this->svy->getSurveyStatus($this->objSurvey->id, $_COOKIE['TLsvy_'.$this->objSurvey->id]);
                }
                if (0 == strcmp($status, 'finished')) {
                    $this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                    $this->Template->hideStartButtons = true;
                }
                break;
            case 'anoncode':
                $this->loadLanguageFile('tl_content');
                $this->Template->needsTAN = true;
                $this->Template->txtTANInputDesc = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start_desc'];
                $this->Template->txtTANInput = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start'];
                if (\strlen(Input::get('code'))) {
                    $this->Template->tancode = Input::get('code');
                }
                break;
            case 'nonanoncode':
                if (!$this->User->id) {
                    $this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_member'];
                    $this->Template->hideStartButtons = true;
                } elseif ($this->objSurvey->limit_groups) {
                    if (!$this->svy->isUserAllowedToTakeSurvey($this->objSurvey)) {
                        $this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_allowed_member'];
                        $this->Template->hideStartButtons = true;
                    }
                } else {
                    $status = $this->svy->getSurveyStatusForMember($this->objSurvey->id, $this->User->id);
                    if (0 == strcmp($status, 'finished')) {
                        $this->Template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                        $this->Template->hideStartButtons = true;
                    }
                }
                break;
        }
    }

    /**
     * @param int $pid Survey id
     * @param int $qid Question id
     * @param string $pin
     * @param mixed $result Result
     * @param int $uid User id
     * @return void
     */
    protected function insertResult($pid, $qid, $pin, $result, $uid = null)
    {
        $newResult = new \Hschottm\SurveyBundle\SurveyResultModel();
        $newResult->tstamp = time();
        $newResult->pid = $pid;
        $newResult->qid = $qid;
        $newResult->pin = $pin;
        $newResult->result = $result;
        if (null != $uid) {
            $newResult->uid = $uid;
        }
        $newResult->save();
    }

    protected function insertPinTan($pid, $pin, $tan, $used)
    {
        $newParticipant = new \Hschottm\SurveyBundle\SurveyPinTanModel();
        $newParticipant->tstamp = time();
        $newParticipant->pid = $pid;
        $newParticipant->pin = $pin;
        $newParticipant->tan = $tan;
        $newParticipant->used = $used;
        $newParticipant->save();
    }

    /**
     * Insert a new participant dataset.
     *
     * @param mixed $pid
     * @param mixed $pin
     * @param mixed $uid
     */
    protected function insertParticipant($pid, $pin, $uid = 0)
    {
        $newParticipant = new \Hschottm\SurveyBundle\SurveyParticipantModel();
        $newParticipant->tstamp = time();
        $newParticipant->pid = $pid;
        $newParticipant->pin = $pin;
        $newParticipant->uid = $uid;
        $newParticipant->save();
    }

    /**
     * Insert a new navigation step
     *
     * @param mixed $pid
     * @param mixed $pin
     * @param mixed $uid
     */
    protected function insertNavigation($pid, $pin, $uid = 0, $from = 0, $to = 0)
    {
        $newNavigation = new \Hschottm\SurveyBundle\SurveyNavigationModel();
        $newNavigation->tstamp = time();
        $newNavigation->pid = $pid;
        $newNavigation->pin = $pin;
        $newNavigation->uid = $uid;
        $newNavigation->frompage = $from;
        $newNavigation->topage = $to;
        $newNavigation->save();
    }

    protected function createResultPage(array $pageData): void
    {
        $templateName = ($pageData['page_template'] ?? '');
        if (!str_starts_with($templateName, 'surveypage_result_')) {
            $templateName = 'surveypage_result_default';
        }

        $resultPageTemplate = new FrontendTemplate($templateName);

        switch ($this->objSurvey->access) {
            case 'anon':
            case 'anoncode':
                $userId = $this->pin;
                break;
            case 'nonanoncode':
                $userId = $this->User->id;
                break;
        }

        $resultPageTemplate->userId = $userId;
        $resultPageTemplate->access = $this->objSurvey->access;
        $this->Template->surveyUserId = $userId;
        $this->Template->surveyUserAccess = $this->objSurvey->access;

        $questions = [];
        /** @var SurveyQuestionModel|SurveyQuestionModel[]|Collection|null $questionCollection */
        $questionCollection = SurveyQuestionModel::findBySurvey($this->objSurvey->id);
        if (!$questionCollection) {
            $resultPageTemplate->results = $questions;
            return;
        }

        $useCategories = false;
        if ($this->objSurvey->useResultCategories) {
            $useCategories = true;
        }

        $count = 0;
        $currentUserCategories = [];
        while ($questionCollection->next()) {
            $count++;
            $questionType = SurveyQuestion::createInstance($questionCollection->id, $questionCollection->questiontype);
            $questions[$count] = [
                'id' => $questionCollection->id,
                'type' => $questionCollection->questiontype,
                'question' => $questionCollection->question,
                'result' => $questionType->getResultData(),
                'questionType' => $questionType,
            ];

            if (!$questionCollection->hidetitle) {
                $questions[$count]['title'] = $questionCollection->title;
            }

            $currentUserResult = SurveyResultModel::findBy(
                ['pid=?', 'qid=?', ($this->objSurvey->access === 'nonanoncode' ? 'uid=?' : 'pin=?')],
                [$this->objSurvey->id, $questionCollection->id, $userId]
            );

            $questions[$count]['currentUserResult'] = null;
            if ($currentUserResult) {
                $questions[$count]['currentUserResult'] = [
                    'result' => $questionType->resultAsString($currentUserResult->result),
                    'data' => $currentUserResult->row(),
                ];

                if ($useCategories && $questionType instanceof SurveyQuestionMultiplechoice) {
                    $result = StringUtil::deserialize($currentUserResult->result ?? '', true)['value'] ?? null;
                    if ($result) {
                        $categoryId = $questionCollection->current()->getCategoryByChoice((int)$result);
                        if ($categoryId || 0 === $categoryId) {
                            $currentUserCategories[$categoryId] = (($currentUserCategories[$categoryId] ?? 0) + 1);
                        }
                    }


                }
            }
        }

        $resultPageTemplate->results = $questions;
        if ($useCategories && !empty($currentUserCategories)) {
            $surveyModel = SurveyModel::findByPk($this->objSurvey->id);
            $userCategories = [];
            $resultCount = array_sum($currentUserCategories);
            $currentMaxCount = 0;
            foreach ($currentUserCategories as $id =>  $value) {
                $userCategories[$id] = [
                    'name' => ($surveyModel ? $surveyModel->getCategoryName($id) : ''),
                    'count' => $value,
                    'percent' => ceil(($value/$resultCount)*100),
                ];
                if ($value > $currentMaxCount) {
                    $resultPageTemplate->currentUserCategory = $userCategories[$id];
                    $currentMaxCount = $value;
                }
            }

            $resultPageTemplate->currentUserCategories = $userCategories;
        }

        $this->Template->questionblock = $resultPageTemplate->parse();
    }
}
