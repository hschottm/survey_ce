<?php

/*
 * @copyright  Helmut Schottmüller 2005-2024 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

use Contao\Controller;
use Hschottm\SurveyBundle\Controller\ContentElement\SurveyController;

$GLOBALS['TL_DCA']['tl_content']['palettes'][SurveyController::TYPE] = '{type_legend},type,headline;{survey_legend},survey;{template_legend:hide},surveyTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['fields']['survey'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['survey'],
    'exclude' => true,
    'inputType' => 'radio',
    'foreignKey' => 'tl_survey.title',
    'eval' => ['mandatory' => true],
    'sql' => "smallint(5) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['surveyTpl'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['surveyTpl'],
    'default' => 'survey',
    'exclude' => true,
    'inputType' => 'select',
	'options_callback' => static function () {
        return Controller::getTemplateGroup('survey');
	},
    'sql' => "varchar(64) NOT NULL default ''",
];
