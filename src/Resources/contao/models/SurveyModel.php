<?php

namespace Hschottm\SurveyBundle;

class SurveyModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_survey';
}

class_alias(SurveyModel::class, 'SurveyModel');
