<?php

namespace Hschottm\SurveyBundle;

use Contao\Model;

class SurveyQuestionModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_survey_question';


  public function findSurveyPageTitleAndQuestionById($id)
  {
      $framework = System::getContainer()->get('contao.framework');
      /** @var SurveyQuestionModel $questionModel */
      $questionModel = $framework->getAdapter(static::class)->findByPk($id);
      if (null === $questionModel) {
          return null;
      }
      $result    = $questionModel->row();
      $pageModel = $framework->getAdapter(SurveyPageModel::class)->findByPk($questionModel->pid);
      if (null !== $pageModel) {
          $result['pagetitle'] = $pageModel->title;
          $result['parentID']  = $pageModel->pid;
      }
      return $result;
  }
}

class_alias(SurveyQuestionModel::class, 'SurveyQuestionModel');
