<?php

namespace Hschottm\SurveyBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\FrontendUser;
use Contao\System;
use Contao\Database;
use Contao\BackendTemplate;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\ContentElement;
use Contao\ArrayUtil;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\File;
use Contao\Validator;
use Contao\Email;
use Contao\Model;
use Contao\Model\Collection;
use Hschottm\SurveyBundle\SurveyPageModel;
use Hschottm\SurveyBundle\SurveyPinTanModel;
use Hschottm\SurveyBundle\SurveyParticipantModel;
use Hschottm\SurveyBundle\SurveyNavigationModel;
use Hschottm\SurveyBundle\SurveyHelper;
use Hschottm\SurveyBundle\SurveyConditionModel;
use Hschottm\SurveyBundle\SurveyQuestionModel;
use Hschottm\SurveyBundle\SurveyResultModel;
use Hschottm\SurveyBundle\Survey;

#[AsContentElement(category: 'texts')]
class SurveyController extends AbstractContentElementController
{
    public const TYPE = 'survey';

    private $User = null;
    private $svy = null;
    private $objSurvey = null;
    private $questionblock_template = 'survey_questionblock';
    private $pin = null;
    private $questionpositions = [];

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')) && !System::getContainer()->get('contao.security.token_checker')->isPreviewMode() && ($this->invisible || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time()))) {
            return new Response('');
        }

        // Get front end user object
        $this->User = FrontendUser::getInstance();

        // add survey javascript
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/hschottmsurvey/js/survey.js';

        $surveyID = (\strlen(Input::post('survey'))) ? Input::post('survey') : $model->survey;

        $this->objSurvey = Database::getInstance()->prepare('SELECT * FROM tl_survey WHERE id=?')->execute($surveyID);
        $this->objSurvey->next();
        if (null == $this->objSurvey) {
            return new Response('');
        }

        $this->svy = new Survey();

        // check date activation
        if ((\strlen($this->objSurvey->online_start)) && ($this->objSurvey->online_start > time())) {
            $template->protected = true;

            return new Response('');
        }
        if ((\strlen($this->objSurvey->online_end)) && ($this->objSurvey->online_end < time())) {
            $template->protected = true;

            return new Response('');
        }

        $pages = SurveyPageModel::findBy('pid', $surveyID, ['order' => 'sorting']);

        if (null == $pages) {
            $pages = [];
        } else {
            $pages = $pages->fetchAll();
        }

        $page = (Input::post('page')) ? Input::post('page') : 0;
        // introduction page / status
        if (0 == $page) {
            $this->outIntroductionPage($template);
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
                            System::setCookie('TLsvy_'.$this->objSurvey->id, $pintan['PIN'], time() + 3600 * 24 * 365, '/');
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
                            $template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_wrong_tan'];
                        } else {
                            $this->pin = $this->svy->getPINforTAN($this->objSurvey->id, $tan);

                            if (0 == $result) {
                                $res = SurveyPinTanModel::findOneBy(['tan=?', 'pid=?'], [$tan, $this->objSurvey->id]);
                                if (null != $res) {
                                    $res->used = 1;
                                    $res->save();
                                }
                                // set pin
                                if ($this->objSurvey->usecookie) {
                                    System::setCookie('TLsvy_'.$this->objSurvey->id, $this->pin, time() + 3600 * 24 * 365, '/');
                                }
                                $this->insertParticipant($this->objSurvey->id, $this->pin);
                                $page = 1;
                            } else {
                                $status = $this->svy->getSurveyStatus($this->objSurvey->id, $this->pin);
                                if (0 == strcmp($status, 'finished')) {
                                    $template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                                    $template->hideStartButtons = true;
                                } else {
                                    $page = $this->svy->getLastPageForPIN($this->objSurvey->id, $this->pin);
                                }
                            }
                        }
                    } else {
                        $template->tanMsg = $GLOBALS['TL_LANG']['ERR']['survey_please_enter_tan'];
                    }
                    break;
                case 'nonanoncode':
                  $participant = SurveyParticipantModel::findOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->frontEndUserID()]);
                  if (null == $participant) {
                    $pintan = $this->svy->generatePIN_TAN();
                    $this->pin = $pintan['PIN'];
                    $this->insertParticipant($this->objSurvey->id, $pintan['PIN'], $this->frontEndUserID());
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
            if (\strlen(Input::post('start'))) {
                $this->insertNavigation($this->objSurvey->id, $this->pin, $this->frontEndUserID(), $previouspage, $page);
                $surveypage = $this->createSurveyPage($pages[$page - 1], $page, false);
            }
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
                $this->insertNavigation($this->objSurvey->id, $this->pin, $this->frontEndUserID(), $previouspage, $page);
                $surveypage = $this->createSurveyPage($pages[$page - 1], $page, false);
            }
            if (\strlen(Input::post('finish'))) {
                $page++;
                $surveypage = $this->createSurveyPage($pages[$page - 1], $page, false);
            }
            if (\strlen(Input::post('prev'))) {
                $res = SurveyNavigationModel::findOneBy(['pid=?', 'pin=?', 'uid=?', 'topage=?'], [$this->objSurvey->id, $this->pin, $this->frontEndUserID(), $page], ['order' => 'tstamp DESC']);
                if (null != $res) {
                  $page = $res->frompage;
                }
                else {
                  $page--;
                }
                $surveypage = $this->createSurveyPage($pages[$page - 1], $page, false);
            }
        }

        // save position of last page (for resume)
        if ($page > 0) {
            $res = SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
            if (null != $res) {
                $res->lastpage = $page;
                $res->save();
            }
            if (\strlen($pages[$page - 1]['page_template'])) {
                $this->questionblock_template = $pages[$page - 1]['page_template'];
            }
        }
        $questionBlockTemplate = new FrontEndTemplate($this->questionblock_template);
        $questionBlockTemplate->surveypage = $surveypage;
                if (is_array($pages))
                {
                        $helper = new SurveyHelper();

			foreach ($pages as $pageidx => $pagerow)
                        {
                                $replacements = [];
                                $pagerow['introduction'] = $helper->replaceTags($pagerow['introduction'], $this->pin, $replacements, true);
                                $pages[$pageidx] = $pagerow;
                        }
                }

        // template output
        $template->pages = $pages;
        $template->survey_id = $this->objSurvey->id;
        $template->show_title = $this->objSurvey->show_title;
        $template->show_cancel = ($page > 0 && \count($surveypage)) ? $this->objSurvey->show_cancel : false;
        $template->surveytitle = StringUtil::specialchars($this->objSurvey->title);
        $template->cancel = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['cancel_survey']);
        global $objPage;
        $template->cancellink = System::getContainer()->get('contao.routing.content_url_generator')->generate($objPage);
        $template->allowback = $this->objSurvey->allowback;

                $qb = $questionBlockTemplate->parse();
                $replacements = [];
                $qb = $helper->replaceTags($qb, $this->pin, $replacements, true);
                $template->questionblock = $qb;


	    $template->page = $page;
        $template->introduction = $this->objSurvey->introduction;
        $template->finalsubmission = ($this->objSurvey->finalsubmission) ? $this->objSurvey->finalsubmission : $GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'];
        $formaction = Environment::get('request');
        
        //$translator = System::getContainer()->get('translator');

        $template->requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
        $template->pageXofY = $GLOBALS['TL_LANG']['MSC']['page_x_of_y'];
        $template->next = $GLOBALS['TL_LANG']['MSC']['survey_next'];
        $template->prev = $GLOBALS['TL_LANG']['MSC']['survey_prev'];
        $template->start = $GLOBALS['TL_LANG']['MSC']['survey_start'];
        $template->finish = $GLOBALS['TL_LANG']['MSC']['survey_finish'];
        $template->pin = $this->pin;
        $template->action = StringUtil::ampersand($formaction);

        return $template->getResponse();
    }

    protected function evaluateConditions($page)
    {
      $conditions = [];
      $conditionModel = SurveyConditionModel::findBy(['pid=?'], [$page['id']]);
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
            $questionModel = SurveyQuestionModel::findOneBy('id', $condition['qid']);
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

    private function frontEndUserID(): int {
        return ($this->User->id) ?? $this->User->id ?? 0;
    }

    protected function getResultForQuestion($question_id)
    {
      $objResult = Database::getInstance()->prepare("SELECT * FROM tl_survey_result WHERE (pid=? AND qid=? AND pin=?)")
                      ->execute($this->objSurvey->id, $question_id, $this->pin);
      if ($objResult->numRows)
      {
              return StringUtil::deserialize($objResult->result);
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

        $questions = SurveyQuestionModel::findBy('pid', $pagerow['id'], ['order' => 'sorting']);

        if (null == $questions) {
            $questions = [];
        }

        foreach ($questions as $questionModel) {
            $question = $questionModel->row();
            $strClass = $GLOBALS['TL_SVY'][$question['questiontype']];
            // Continue if the class is not defined
            if (!class_exists($strClass)) {
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
                        $objResult = SurveyResultModel::findBy(['pid=?', 'qid=?', 'pin=?'], [$this->objSurvey->id, $objWidget->id, $this->pin]);
                        break;
                    case 'nonanoncode':
                        $objResult = SurveyResultModel::findBy(['pid=?', 'qid=?', 'uid=?'], [$this->objSurvey->id, $objWidget->id, $this->frontEndUserID()]);
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
                $this->redirect(System::getContainer()->get('contao.routing.content_url_generator')->generate($objPage));
            }
        }

        // save survey values
        if ($validate && 'tl_survey' == Input::post('FORM_SUBMIT') && (!$doNotSubmit || $goback)) {
            if (!\strlen($this->pin) || !$this->isValid($this->pin)) {
                global $objPage;
                $this->redirect(System::getContainer()->get('contao.routing.content_url_generator')->generate($objPage));
            }
            foreach ($surveypage as $question) {
                switch ($this->objSurvey->access) {
                    case 'anon':
                    case 'anoncode':
                      $res = SurveyResultModel::findBy(['pid=?', 'qid=?', 'pin=?'], [$this->objSurvey->id, $question->id, $this->pin]);
                      if (null != $res) {
                        if ($res instanceof Model) {
                          $res->delete();
                        } elseif ($res instanceof Collection) {
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
                        $res = SurveyResultModel::findBy(['pid=?', 'qid=?', 'uid=?'], [$this->objSurvey->id, $question->id, $this->frontEndUserID()]);
                        if (null != $res) {
                            if ($res instanceof Model) {
                                $res->delete();
                            } elseif ($res instanceof Collection) {
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
                            $this->insertResult($this->objSurvey->id, $question->id, $this->pin, $value, $this->frontEndUserID());
                        }
                        break;
                }
            }

            if (Input::post('finish')) {
                // finish the survey
                switch ($this->objSurvey->access) {
                    case 'anon':
                    case 'anoncode':
            $participant = SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$this->objSurvey->id, $this->pin]);
            $participant->finished = 1;
            $participant->save();
                        break;
                    case 'nonanoncode':
            $participant = SurveyParticipantModel::findOneBy(['pid=?', 'uid=?'], [$this->objSurvey->id, $this->frontEndUserID()]);
            $participant->finished = 1;
            $participant->save();
                        break;
                }
                // HOOK: pass survey data to callback functions when survey is finished
                if (isset($GLOBALS['TL_HOOKS']['surveyFinished']) && \is_array($GLOBALS['TL_HOOKS']['surveyFinished'])) {
                    foreach ($GLOBALS['TL_HOOKS']['surveyFinished'] as $callback) {
                        $this->import($callback[0]);
                        $this->$callback[0]->$callback[1]($this->objSurvey->row());
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
        					list($senderName, $sender) = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailSender);
        					$objMailProperties->sender = $sender;
        					$objMailProperties->senderName = $senderName;

        					// Set the 'reply to' address, if given in form configuration
        					if (!empty($this->objSurvey->confirmationMailReplyto))
        					{
        						list($replyToName, $replyTo) = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailReplyto);
        						$objMailProperties->replyTo = (strlen($replyToName) ? $replyToName . ' <' . $replyTo . '>' : $replyTo);
        					}

        					// Set recipient(s)
                            $arrRecipient = array();
        					if (strlen($this->objSurvey->confirmationMailRecipientField))
        					{
                                $res = SurveyResultModel::findOneBy(['qid=?', 'pin=?'], [$this->objSurvey->confirmationMailRecipientField, $this->pin]);
                                if (null != $res) {
                                    if (strlen($res->result))
          						    {
          							    $arrRecipient = StringUtil::trimsplit(',', $res->result);
          						    }
                                }
        					}

        					if (!empty($this->objSurvey->confirmationMailRecipient))
        					{
        						$varRecipient = $this->objSurvey->confirmationMailRecipient;
        						$arrRecipient = array_merge($arrRecipient, StringUtil::trimsplit(',', $varRecipient));
        					}
        					$arrRecipient = array_filter(array_unique($arrRecipient));

        					if (!empty($arrRecipient))
        					{
        						foreach ($arrRecipient as $kR => $recipient)
        						{
        							list($recipientName, $recipient) = StringUtil::splitFriendlyEmail(System::getContainer()->get('contao.insert_tag.parser')->replace($recipient));
        							$arrRecipient[$kR] = (strlen($recipientName) ? $recipientName . ' <' . $recipient . '>' : $recipient);
        						}
        					}
        					$objMailProperties->recipients = $arrRecipient;
        					// Check if we want custom attachments... (Thanks to Torben Schwellnus)
        					if ($this->objSurvey->addConfirmationMailAttachments)
        					{
        						if($this->objSurvey->confirmationMailAttachments)
        						{
        							$arrCustomAttachments = StringUtil::deserialize($this->objSurvey->confirmationMailAttachments, true);

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
          					list($senderName, $sender) = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailAlternateSender);
          					$objMailProperties->sender = $sender;
          					$objMailProperties->senderName = $senderName;

          					// Set the 'reply to' address, if given in form configuration
          					if (!empty($this->objSurvey->confirmationMailAlternateReplyto))
          					{
          						list($replyToName, $replyTo) = StringUtil::splitFriendlyEmail($this->objSurvey->confirmationMailAlternateReplyto);
          						$objMailProperties->replyTo = (strlen($replyToName) ? $replyToName . ' <' . $replyTo . '>' : $replyTo);
          					}

          					// Set recipient(s)
                    $arrRecipient = [];
          					if (!empty($this->objSurvey->confirmationMailAlternateRecipient))
          					{
          						$varRecipient = $this->objSurvey->confirmationMailAlternateRecipient;
          						$arrRecipient = array_merge($arrRecipient, StringUtil::trimsplit(',', $varRecipient));
          					}
          					$arrRecipient = array_filter(array_unique($arrRecipient));

          					if (!empty($arrRecipient))
          					{
          						foreach ($arrRecipient as $kR => $recipient)
          						{
          							list($recipientName, $recipient) = StringUtil::splitFriendlyEmail(System::getContainer()->get('contao.insert_tag.parser')->replace($recipient));
          							$arrRecipient[$kR] = (strlen($recipientName) ? $recipientName . ' <' . $recipient . '>' : $recipient);
          						}
          					}
          					$objMailProperties->recipients = $arrRecipient;
          					// Check if we want custom attachments... (Thanks to Torben Schwellnus)
          					if ($this->objSurvey->addConfirmationMailAlternateAttachments)
          					{
          						if($this->objSurvey->confirmationMailAlternateAttachments)
          						{
          							$arrCustomAttachments = StringUtil::deserialize($this->objSurvey->confirmationMailAlternateAttachments, true);

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
                $execute = (method_exists(Database::getInstance(), 'executeUncached')) ? 'executeUncached' : 'execute';
                $this->questionpositions = Database::getInstance()->prepare('SELECT tl_survey_question.id FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? ORDER BY tl_survey_page.sorting, tl_survey_question.sorting')
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
        $participants = SurveyParticipantModel::findBy(['pin=?', 'pid=?'], [$pin, $this->objSurvey->id]);
        if (null == $participants) {
            return false;
        }
        if (1 == $participants->count()) {
            return true;
        }

        return false;
    }

    protected function outIntroductionPage(Template $template)
    {
        switch ($this->objSurvey->access) {
            case 'anon':
                $status = '';
                if ($this->objSurvey->usecookie) {
                    $status = $this->svy->getSurveyStatus($this->objSurvey->id, $_COOKIE['TLsvy_'.$this->objSurvey->id]);
                }
                if (0 == strcmp($status, 'finished')) {
                    $template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                    $template->hideStartButtons = true;
                }
                break;
            case 'anoncode':
                System::loadLanguageFile('tl_content');
                $template->needsTAN = true;
                $template->txtTANInputDesc = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start_desc'];
                $template->txtTANInput = $GLOBALS['TL_LANG']['tl_content']['enter_tan_to_start'];
                if (\strlen(Input::get('code'))) {
                    $template->tancode = Input::get('code');
                }
                break;
            case 'nonanoncode':
                if (!$this->User->id) {
                    $template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_member'];
                    $template->hideStartButtons = true;
                } elseif ($this->objSurvey->limit_groups) {
                    if (!$this->svy->isUserAllowedToTakeSurvey($this->objSurvey)) {
                        $template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_no_allowed_member'];
                        $template->hideStartButtons = true;
                    }
                } else {
                    $status = $this->svy->getSurveyStatusForMember($this->objSurvey->id, $this->frontEndUserID());
                    if (0 == strcmp($status, 'finished')) {
                        $template->errorMsg = $GLOBALS['TL_LANG']['ERR']['survey_already_finished'];
                        $template->hideStartButtons = true;
                    }
                }
                break;
        }
    }

    protected function insertResult($pid, $qid, $pin, $result, $uid = null)
    {
        $newResult = new SurveyResultModel();
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
        $newParticipant = new SurveyPinTanModel();
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
        $newParticipant = new SurveyParticipantModel();
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
        $newNavigation = new SurveyNavigationModel();
        $newNavigation->tstamp = time();
        $newNavigation->pid = $pid;
        $newNavigation->pin = $pin;
        $newNavigation->uid = $uid;
        $newNavigation->frompage = $from;
        $newNavigation->topage = $to;
        $newNavigation->save();
    }
}
