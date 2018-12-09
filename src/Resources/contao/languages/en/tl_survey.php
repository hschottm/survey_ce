<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey']['title'] = ['Title', 'Please enter the survey title.'];
$GLOBALS['TL_LANG']['tl_survey']['author'] = ['Author', 'Please enter the author name.'];
$GLOBALS['TL_LANG']['tl_survey']['description'] = ['Description', 'Please enter a survey description.'];
$GLOBALS['TL_LANG']['tl_survey']['tstamp'] = ['Last change', 'Date and time of the last change.'];
$GLOBALS['TL_LANG']['tl_survey']['language'] = ['Language', 'Please select the survey language.'];
$GLOBALS['TL_LANG']['tl_survey']['introduction'] = ['Introduction', 'Please enter a survey introduction. The introduction will be shown on the start page of the survey.'];
$GLOBALS['TL_LANG']['tl_survey']['finalsubmission'] = ['Final statement', 'Please enter a final statement. The final statement will be shown when the survey is finished.'];
$GLOBALS['TL_LANG']['tl_survey']['online_start'] = ['Show from', 'Do not show the survey on the website before this day.'];
$GLOBALS['TL_LANG']['tl_survey']['online_end'] = ['Show until', 'Do not show the page on the website after this day.'];
$GLOBALS['TL_LANG']['tl_survey']['limit_groups'] = ['Limit members', 'Limit the access to selected member groups.'];
$GLOBALS['TL_LANG']['tl_survey']['allowed_groups'] = ['Member groups', 'Choose the member groups that should be able to participate in the survey.'];
$GLOBALS['TL_LANG']['tl_survey']['access'] = ['Survey access', 'Choose the appropriate access method for the survey.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['explanation'] = 'Please choose the appropriate access method for the survey.';
$GLOBALS['TL_LANG']['tl_survey']['access']['anon'] = ['Anonymized access', 'Everyone can participate in the survey, even more than once. Access is anonymized. Survey results cannot be tracked back to a participant.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['anoncode'] = ['Anonymized access with TAN code', 'Only participants with a valid TAN code can participate in the survey. A survey can be finished only once per TAN code. Access is anonymized. Survey result can be tracked back to each TAN.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['nonanoncode'] = ['Personalized access', 'Only participants with a valid frontend login can participate in the survey. A survey can be finished only once per participant. Survey results can be tracked back to each participant.'];
$GLOBALS['TL_LANG']['tl_survey']['usecookie'] = ['Remember participants', 'Remembers a survey participant using a cookie.'];
$GLOBALS['TL_LANG']['tl_survey']['show_title'] = ['Show survey title', 'Always show the survey title on top of the survey.'];
$GLOBALS['TL_LANG']['tl_survey']['show_cancel'] = ['Show cancel', 'Always show an <strong>Exit this survey</strong> command on top of the survey.'];
$GLOBALS['TL_LANG']['tl_survey']['allowback'] = ['Show "Previous" button', 'Shows a "Previous" button in the survey navigation to go back to the previous page.'];
$GLOBALS['TL_LANG']['tl_survey']['immediate_start'] = ['Start survey immediately', 'Check this option if you want to show the form immediately.'];
$GLOBALS['TL_LANG']['tl_survey']['jumpto'] = ['Redirect to page', 'Select a page to redirect the survey after it was finished.'];
$GLOBALS['TL_LANG']['tl_survey']['surveyPage'] = ['Survey page', 'Please choose the page that contains the survey. If a page is selected an URL to the survey containing the TAN code will be created for export.'];

$GLOBALS['TL_LANG']['tl_survey']['new'] = ['New survey', 'Create a new survey'];
$GLOBALS['TL_LANG']['tl_survey']['show'] = ['Survey details', 'Show the details of survey %s'];
$GLOBALS['TL_LANG']['tl_survey']['edit'] = ['Edit survey', 'Edit survey ID %s'];
$GLOBALS['TL_LANG']['tl_survey']['edit_'] = ['You cannot edit the survey', 'Survey ID %s is locked. Participant results already exist.'];
$GLOBALS['TL_LANG']['tl_survey']['participants'] = ['Survey participants', 'Edit participants of survey ID %s'];
$GLOBALS['TL_LANG']['tl_survey']['statistics'] = ['Statistics', 'Show statistics of survey ID %s'];
$GLOBALS['TL_LANG']['tl_survey']['pintan'] = ['TAN codes', 'Create TAN codes for survey ID %s'];
$GLOBALS['TL_LANG']['tl_survey']['copy'] = ['Duplicate survey', 'Duplicate survey ID %s'];
$GLOBALS['TL_LANG']['tl_survey']['delete'] = ['Delete survey', 'Delete survey ID %s'];

/*
* Legends
*/
$GLOBALS['TL_LANG']['tl_survey']['head_legend'] = 'Head settings';
$GLOBALS['TL_LANG']['tl_survey']['title_legend'] = 'Title and description';
$GLOBALS['TL_LANG']['tl_survey']['activation_legend'] = 'Activation';
$GLOBALS['TL_LANG']['tl_survey']['access_legend'] = 'Access';
$GLOBALS['TL_LANG']['tl_survey']['texts_legend'] = 'Statements';
$GLOBALS['TL_LANG']['tl_survey']['misc_legend'] = 'General settings';
