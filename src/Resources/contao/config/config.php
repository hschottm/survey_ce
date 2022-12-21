<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */

use Hschottm\SurveyBundle\ConditionWizard;
use Hschottm\SurveyBundle\ContentSurvey;
use Hschottm\SurveyBundle\FormConstantSumQuestion;
use Hschottm\SurveyBundle\FormMatrixQuestion;
use Hschottm\SurveyBundle\FormMultipleChoiceQuestion;
use Hschottm\SurveyBundle\FormOpenEndedQuestion;
use Hschottm\SurveyBundle\SurveyPINTAN;
use Hschottm\SurveyBundle\SurveyQuestionConstantsum;
use Hschottm\SurveyBundle\SurveyQuestionMatrix;
use Hschottm\SurveyBundle\SurveyQuestionMultiplechoice;
use Hschottm\SurveyBundle\SurveyQuestionOpenended;
use Hschottm\SurveyBundle\SurveyResultDetails;

/*
 * Add survey element
 */
array_insert($GLOBALS['TL_CTE']['includes'], 2, [
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
array_insert($GLOBALS['BE_MOD'], 3, [
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
            'icon' => 'bundles/hschottmsurvey/images/scale.png',
        ],
    ],
]);

if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/hschottmsurvey/css/survey.css|static';
}

array_insert($GLOBALS['BE_FFL'], 15, [
    'conditionwizard' => ConditionWizard::class,
]);

$GLOBALS['BE_MOD']['surveys']['survey']['exportraw'] = [SurveyResultDetails::class, 'exportResultsRaw'];

$GLOBALS['TL_SVY']['openended'] = FormOpenEndedQuestion::class;
$GLOBALS['TL_SVY']['multiplechoice'] = FormMultipleChoiceQuestion::class;
$GLOBALS['TL_SVY']['matrix'] = FormMatrixQuestion::class;
$GLOBALS['TL_SVY']['constantsum'] = FormConstantSumQuestion::class;

$GLOBALS['TL_SVY']['q_openended'] = SurveyQuestionOpenended::class;
$GLOBALS['TL_SVY']['q_multiplechoice'] = SurveyQuestionMultiplechoice::class;
$GLOBALS['TL_SVY']['q_matrix'] = SurveyQuestionMatrix::class;
$GLOBALS['TL_SVY']['q_constantsum'] = SurveyQuestionConstantsum::class;

/*
 * Set the member URL parameter as url keyword
 */
if (isset($GLOBALS['TL_CONFIG']['urlKeywords'])) {
    $GLOBALS['TL_CONFIG']['urlKeywords'] .= (strlen(trim($GLOBALS['TL_CONFIG']['urlKeywords'])) ? ',' : '').'code';
}
