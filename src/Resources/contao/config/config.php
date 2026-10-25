<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

use Hschottm\SurveyBundle\ContentSurvey;
use Hschottm\SurveyBundle\FormConstantSumQuestion;
use Hschottm\SurveyBundle\FormMatrixQuestion;
use Hschottm\SurveyBundle\FormMultipleChoiceQuestion;
use Hschottm\SurveyBundle\FormOpenEndedQuestion;
use Hschottm\SurveyBundle\SurveyPINTAN;
use Hschottm\SurveyBundle\SurveyResultDetails;
use Hschottm\SurveyBundle\SurveyQuestionOpenended;
use Hschottm\SurveyBundle\SurveyQuestionMultiplechoice;
use Hschottm\SurveyBundle\SurveyQuestionMatrix;
use Hschottm\SurveyBundle\SurveyQuestionConstantsum;
use Hschottm\SurveyBundle\ConditionWizard;


/*
 * Add survey element
 */
\Contao\ArrayUtil::arrayInsert($GLOBALS['TL_CTE']['includes'], 2, [
    'survey' => ContentSurvey::class,
]);

/*
* Add frontend widgets
*/

/*
 * FRONT END MODULES
 */

/*
 * BACK END FORM FIELDS
 */
\Contao\ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 3, [
    'surveys' => [
            'survey' => [
                    'tables' => [
                            'tl_survey', 'tl_survey_page', 'tl_survey_question', 'tl_survey_participant', 'tl_survey_pin_tan',
                        ],
                    'scale' => ['tl_survey_question', 'addScale'],
                    'export' => [SurveyResultDetails::class, 'exportResults'],
                    'createtan' => [SurveyPINTAN::class, 'createTAN'],
                    'exporttan' => [SurveyPINTAN::class, 'exportTAN'],
                    'cumulated' => [SurveyResultDetails::class, 'showCumulated'],
                    'details' => [SurveyResultDetails::class, 'showDetails'],
                ],
            'scale' => [
                    'tables' => [
                            'tl_survey_scale_folder', 'tl_survey_scale',
                        ],
                    'icon' => 'bundles/hschottmsurvey/images/scale.svg',
                ],
        ],
]);

if (\Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest()) {
    $GLOBALS['TL_CSS'][] = 'bundles/hschottmsurvey/css/survey.css|static';
    $GLOBALS['TL_CSS'][] = 'bundles/hschottmsurvey/css/condition-editor.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/hschottmsurvey/js/condition-editor.js|static';
}

\Contao\ArrayUtil::arrayInsert($GLOBALS['BE_FFL'], 15, array
(
	'conditionwizard'    => ConditionWizard::class
));

$GLOBALS['BE_MOD']['surveys']['survey']['exportraw'] = [SurveyResultDetails::class, 'exportResultsRaw'];

$GLOBALS['TL_SVY']['openended'] = FormOpenEndedQuestion::class;
$GLOBALS['TL_SVY']['multiplechoice'] = FormMultipleChoiceQuestion::class;
$GLOBALS['TL_SVY']['matrix'] = FormMatrixQuestion::class;
$GLOBALS['TL_SVY']['constantsum'] = FormConstantSumQuestion::class;

$GLOBALS['TL_SVY']['q_openended'] = SurveyQuestionOpenended::class;
$GLOBALS['TL_SVY']['q_multiplechoice'] = SurveyQuestionMultiplechoice::class;
$GLOBALS['TL_SVY']['q_matrix'] = SurveyQuestionMatrix::class;
$GLOBALS['TL_SVY']['q_constantsum'] = SurveyQuestionConstantsum::class;


// Explicit registration is required for namespaced models in Contao 5.
$GLOBALS['TL_MODELS']['tl_survey_navigation'] = \Hschottm\SurveyBundle\SurveyNavigationModel::class;
$GLOBALS['TL_MODELS']['tl_survey_page'] = \Hschottm\SurveyBundle\SurveyPageModel::class;
$GLOBALS['TL_MODELS']['tl_survey'] = \Hschottm\SurveyBundle\SurveyModel::class;
$GLOBALS['TL_MODELS']['tl_survey_question'] = \Hschottm\SurveyBundle\SurveyQuestionModel::class;
$GLOBALS['TL_MODELS']['tl_survey_condition'] = \Hschottm\SurveyBundle\SurveyConditionModel::class;
$GLOBALS['TL_MODELS']['tl_survey_pin_tan'] = \Hschottm\SurveyBundle\SurveyPinTanModel::class;
$GLOBALS['TL_MODELS']['tl_survey_result'] = \Hschottm\SurveyBundle\SurveyResultModel::class;
$GLOBALS['TL_MODELS']['tl_survey_participant'] = \Hschottm\SurveyBundle\SurveyParticipantModel::class;
