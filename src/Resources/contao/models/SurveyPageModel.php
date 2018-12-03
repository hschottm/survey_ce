<?php

namespace Hschottm\SurveyBundle;

use Contao\Model;

class SurveyPageModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_survey_page';
}

class_alias(SurveyPageModel::class, 'SurveyPageModel');
