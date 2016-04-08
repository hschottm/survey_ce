<?php

/**
 * Add survey element
 */
array_insert($GLOBALS['TL_CTE']['includes'], 2, array
(
	'survey' => 'ContentSurvey'
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
					'export' => array('SurveyResultDetails', 'exportResults'),
					'createtan' => array('SurveyPINTAN', 'createTAN'),
					'exporttan' => array('SurveyPINTAN', 'exportTAN'),
					'cumulated' => array('SurveyResultDetails', 'showCumulated'),
					'details' => array('SurveyResultDetails', 'showDetails'),
					'icon' => 'system/modules/survey_ce/assets/survey.png',
					'stylesheet' => 'system/modules/survey_ce/assets/survey.css'
				),
			"scale" => array(
					"tables" => array(
							"tl_survey_scale_folder", "tl_survey_scale"
						),
					'icon' => 'system/modules/survey_ce/assets/scale.png'
				)
		)
));

$GLOBALS['BE_MOD']['surveys']['survey']['exportraw'] = array('SurveyResultDetailsEx', 'exportResultsRaw');

$GLOBALS['TL_SVY']['openended'] = 'FormOpenEndedQuestion';
$GLOBALS['TL_SVY']['multiplechoice'] = 'FormMultipleChoiceQuestion';
$GLOBALS['TL_SVY']['matrix'] = 'FormMatrixQuestion';
$GLOBALS['TL_SVY']['constantsum'] = 'FormConstantSumQuestion';

$GLOBALS['TL_SVY']['q_openended'] = 'SurveyQuestionOpenended';
$GLOBALS['TL_SVY']['q_multiplechoice'] = 'SurveyQuestionMultiplechoice';
$GLOBALS['TL_SVY']['q_matrix'] = 'SurveyQuestionMatrix';
$GLOBALS['TL_SVY']['q_constantsum'] = 'SurveyQuestionConstantsum';

/**
 * Set the member URL parameter as url keyword
 */
$GLOBALS['TL_CONFIG']['urlKeywords'] .= (strlen(trim($GLOBALS['TL_CONFIG']['urlKeywords'])) ? ',' : '') . "code";

