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

$GLOBALS['TL_LANG']['CTE']['survey'] = ['Survey', 'includes a survey.'];
$GLOBALS['TL_LANG']['MSC']['deleteAll'] = 'Delete all participants data';
$GLOBALS['TL_LANG']['MSC']['page_x_of_y'] = 'Page %s of %s';
$GLOBALS['TL_LANG']['MSC']['survey_next'] = 'Next';
$GLOBALS['TL_LANG']['MSC']['survey_prev'] = 'Previous';
$GLOBALS['TL_LANG']['MSC']['survey_start'] = 'Start survey';
$GLOBALS['TL_LANG']['MSC']['survey_finish'] = 'Finish survey';
$GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'] = 'Thank you for participating in this survey.';
$GLOBALS['TL_LANG']['MSC']['cancel_survey'] = 'Exit this survey';

$GLOBALS['TL_LANG']['ERR']['survey_already_finished'] = 'You already finished the survey.';
$GLOBALS['TL_LANG']['ERR']['survey_please_enter_tan'] = 'Please enter a TAN code to start the survey.';
$GLOBALS['TL_LANG']['ERR']['survey_wrong_tan'] = 'Invalid TAN code. Please enter a valid TAN code.';
$GLOBALS['TL_LANG']['ERR']['survey_no_member'] = 'You have to log on as a member to participate in this survey.';
$GLOBALS['TL_LANG']['ERR']['survey_no_allowed_member'] = 'You don\'t have the permission to participate in this survey.';
$GLOBALS['TL_LANG']['ERR']['sumnotexact'] = 'The sum of the entered values of question "%s" is different from %s.';
$GLOBALS['TL_LANG']['ERR']['sumnotmax'] = 'The sum of the entered values of question "%s" is greater than %s.';
$GLOBALS['TL_LANG']['ERR']['selectoption'] = 'Please select an option.';
$GLOBALS['TL_LANG']['ERR']['mandatory_constantsum'] = 'Please fill in the question "%s" completely.';
$GLOBALS['TL_LANG']['ERR']['mandatory_matrix'] = 'Please check a least one option in every row of question "%s".';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'] = 'Please check exactly one answer of question "%s".';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_mr'] = 'Please check at least on answer of question "%s".';
$GLOBALS['TL_LANG']['ERR']['missing_other_value'] = 'You checked the additional answer but you didn\'t enter a text.';
$GLOBALS['TL_LANG']['ERR']['lower_bound'] = 'Value (%s) for "%s" is smaller than allowed (%s).';
$GLOBALS['TL_LANG']['ERR']['upper_bound'] = 'Value (%s) for "%s" is greater than allowed (%s).';
