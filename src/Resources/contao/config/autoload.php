<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Survey_ce
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\ContentSurvey'                  => 'system/modules/survey_ce/classes/ContentSurvey.php',
	'Contao\FormConstantSumQuestion'        => 'system/modules/survey_ce/classes/FormConstantSumQuestion.php',
	'Contao\FormMatrixQuestion'             => 'system/modules/survey_ce/classes/FormMatrixQuestion.php',
	'Contao\FormMultipleChoiceQuestion'     => 'system/modules/survey_ce/classes/FormMultipleChoiceQuestion.php',
	'Contao\FormOpenEndedQuestion'          => 'system/modules/survey_ce/classes/FormOpenEndedQuestion.php',
	'Contao\FormQuestionWidget'             => 'system/modules/survey_ce/classes/FormQuestionWidget.php',
	'Contao\Survey'                         => 'system/modules/survey_ce/classes/Survey.php',
	'Contao\SurveyPagePreview'              => 'system/modules/survey_ce/classes/SurveyPagePreview.php',
	'Contao\SurveyPINTAN'                   => 'system/modules/survey_ce/classes/SurveyPINTAN.php',
	'Contao\SurveyQuestion'                 => 'system/modules/survey_ce/classes/SurveyQuestion.php',
	'Contao\SurveyQuestionConstantsum'      => 'system/modules/survey_ce/classes/SurveyQuestionConstantsum.php',
	'Contao\SurveyQuestionConstantsumEx'    => 'system/modules/survey_ce/classes/SurveyQuestionConstantsumEx.php',
	'Contao\SurveyQuestionMatrix'           => 'system/modules/survey_ce/classes/SurveyQuestionMatrix.php',
	'Contao\SurveyQuestionMatrixEx'         => 'system/modules/survey_ce/classes/SurveyQuestionMatrixEx.php',
	'Contao\SurveyQuestionMultiplechoice'   => 'system/modules/survey_ce/classes/SurveyQuestionMultiplechoice.php',
	'Contao\SurveyQuestionMultiplechoiceEx' => 'system/modules/survey_ce/classes/SurveyQuestionMultiplechoiceEx.php',
	'Contao\SurveyQuestionOpenended'        => 'system/modules/survey_ce/classes/SurveyQuestionOpenended.php',
	'Contao\SurveyQuestionOpenendedEx'      => 'system/modules/survey_ce/classes/SurveyQuestionOpenendedEx.php',
	'Contao\SurveyQuestionPreview'          => 'system/modules/survey_ce/classes/SurveyQuestionPreview.php',
	'Contao\SurveyResultDetails'            => 'system/modules/survey_ce/classes/SurveyResultDetails.php',
	'Contao\SurveyResultDetailsEx'          => 'system/modules/survey_ce/classes/SurveyResultDetailsEx.php',

	// Models
	'Contao\SurveyResultModel'              => 'system/modules/survey_ce/models/SurveyResultModel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_question_result_details'     => 'system/modules/survey_ce/templates',
	'be_survey_create_tan'           => 'system/modules/survey_ce/templates',
	'be_survey_export_tan'           => 'system/modules/survey_ce/templates',
	'be_survey_page_preview'         => 'system/modules/survey_ce/templates',
	'be_survey_question_preview'     => 'system/modules/survey_ce/templates',
	'be_survey_result_cumulated'     => 'system/modules/survey_ce/templates',
	'ce_survey'                      => 'system/modules/survey_ce/templates',
	'ce_survey_blue_smileys'         => 'system/modules/survey_ce/templates',
	'form_constantsum'               => 'system/modules/survey_ce/templates',
	'form_matrix'                    => 'system/modules/survey_ce/templates',
	'form_multiplechoice'            => 'system/modules/survey_ce/templates',
	'form_openended'                 => 'system/modules/survey_ce/templates',
	'survey_answers_constantsum'     => 'system/modules/survey_ce/templates',
	'survey_answers_default'         => 'system/modules/survey_ce/templates',
	'survey_answers_matrix'          => 'system/modules/survey_ce/templates',
	'survey_answers_multiplechoice'  => 'system/modules/survey_ce/templates',
	'survey_question_constantsum'    => 'system/modules/survey_ce/templates',
	'survey_question_matrix'         => 'system/modules/survey_ce/templates',
	'survey_question_multiplechoice' => 'system/modules/survey_ce/templates',
	'survey_question_openended'      => 'system/modules/survey_ce/templates',
	'survey_questionblock'           => 'system/modules/survey_ce/templates',
	'survey_questionblock_table'     => 'system/modules/survey_ce/templates',
));
