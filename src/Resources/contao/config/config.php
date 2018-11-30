<?php

use Hschottm\SurveyBundle\ContentSurvey;
use Hschottm\SurveyBundle\SurveyResultDetails;
use Hschottm\SurveyBundle\SurveyResultDetailsEx;
use Hschottm\SurveyBundle\SurveyPINTAN;
use Hschottm\SurveyBundle\FormOpenEndedQuestion;
use Hschottm\SurveyBundle\FormMultipleChoiceQuestion;
use Hschottm\SurveyBundle\FormMatrixQuestion;
use Hschottm\SurveyBundle\FormConstantSumQuestion;

/**
 * Add survey element
 */
array_insert($GLOBALS['TL_CTE']['includes'], 2, array
(
	'survey' => ContentSurvey::class
));

/**
* Add frontend widgets
*/

/**
 * FRONT END MODULES
 */

/**
 * BACK END FORM FIELDS
 */
array_insert($GLOBALS['BE_MOD'], 3, array
(
	"surveys" => array(
			"survey" => array(
					"tables" => array(
							"tl_survey", "tl_survey_page", "tl_survey_question", "tl_survey_participant", "tl_survey_pin_tan"
						),
					'scale' => array('tl_survey_question', 'addScale'),
					'export' => array(SurveyResultDetails::class, 'exportResults'),
					'createtan' => array(SurveyPINTAN::class, 'createTAN'),
					'exporttan' => array(SurveyPINTAN::class, 'exportTAN'),
					'cumulated' => array(SurveyResultDetails::class, 'showCumulated'),
					'details' => array(SurveyResultDetails:class, 'showDetails'),
					'icon' => 'bundles/hschottmsurveybundle/images/survey.png',
					'stylesheet' => 'bundles/hschottmsurveybundle/css/survey.css'
				),
			"scale" => array(
					"tables" => array(
							"tl_survey_scale_folder", "tl_survey_scale"
						),
					'icon' => 'bundles/hschottmsurveybundle/images/scale.png'
				)
		)
));

$GLOBALS['BE_MOD']['surveys']['survey']['exportraw'] = array(SurveyResultDetailsEx::class, 'exportResultsRaw');

$GLOBALS['TL_SVY']['openended'] = FormOpenEndedQuestion::class;
$GLOBALS['TL_SVY']['multiplechoice'] = FormMultipleChoiceQuestion::class;
$GLOBALS['TL_SVY']['matrix'] = FormMatrixQuestion::class;
$GLOBALS['TL_SVY']['constantsum'] = FormConstantSumQuestion::class;

/**
 * Set the member URL parameter as url keyword
 */
$GLOBALS['TL_CONFIG']['urlKeywords'] .= (strlen(trim($GLOBALS['TL_CONFIG']['urlKeywords'])) ? ',' : '') . "code";
