<?php

namespace Hschottm\SurveyBundle;

use Contao\Model;

class SurveyPinTanModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_survey_pin_tan';
}

class_alias(SurveyPinTanModel::class, 'SurveyPinTanModel');
