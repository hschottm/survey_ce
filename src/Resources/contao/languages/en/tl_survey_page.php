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

$GLOBALS['TL_LANG']['tl_survey_page']['title'] = ['Title', 'Please enter the page title.'];
$GLOBALS['TL_LANG']['tl_survey_page']['description'] = ['Description', 'Please enter the page description.'];
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'] = ['Last change', 'Date and time of the last change.'];
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'] = ['Introduction', 'Please enter a page introduction. The introduction will be shown at the beginning of the page.'];
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'] = ['Page template', 'Here you can select the page template.'];
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'] = ['Jump conditions', 'If you do not want to continue with the next page, define jump conditions to continue with a another page.'];

$GLOBALS['TL_LANG']['tl_survey_page']['new'] = ['New page', 'Create a new page'];
$GLOBALS['TL_LANG']['tl_survey_page']['show'] = ['Show details', 'Show details of page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['cut'] = ['Move page', 'Move page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['edit'] = ['Edit page', 'Edit page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['copy'] = ['Duplicate page', 'Duplicate page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['delete'] = ['Delete page', 'Delete page ID %s'];

$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Page';
$GLOBALS['TL_LANG']['tl_survey_page']['type'] = ['Page type', 'Please choose the type of the page.'];
$GLOBALS['TL_LANG']['tl_survey_page']['useCustomNextButtonTitle'] = [
    'Adjust "Next" text',
    'Choose this option to use a custom next button text on the former page.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['customNextButtonTitle'] = [
    '"Next" text',
    'Set a text for the next button on the former page.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['hideBackButton'] = [
    'Hide "back"',
    'Choose this option to hide the back button on the current page.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['markSurveyAsFinished'] = [
    'Mark survey as finished',
    'Choose this option to mark the survey as finished when reaching the current page.',
];

/*
* Legends
*/
$GLOBALS['TL_LANG']['tl_survey_page']['type_legend'] = 'Page type';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Title and description';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introduction';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Jump conditions';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Template settings';
$GLOBALS['TL_LANG']['tl_survey_page']['config_legend'] = 'Configuration';

/*
 * Page types
 */
$GLOBALS['TL_LANG']['tl_survey_page']['type']['default'] = 'Question page';
$GLOBALS['TL_LANG']['tl_survey_page']['type']['result'] = 'Result page';
