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

$GLOBALS['TL_LANG']['tl_survey']['skipEmpty'] = array('Skip empty fields', 'Hide empty fields in the e-mail.');
$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMail'] = array('Send confirmation via e-mail', 'If you choose this option, a confirmation will be sent via e-mail to the sender of the form.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipientField'] = array('Form field containing e-mail of recipient', 'Choose the form field in which the user enters his e-mail address or a field containing the e-mail address as value.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipient'] = array('Recipient', 'Please enter one or more recipient e-mail addresses if e-mail address is not defined by a form field or if you want e-mail to be sent to additional recipients. Separate multiple addresses with comma.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSender'] = array('Sender', 'Please enter e-mail address to be used as sender.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailReplyto'] = array('Reply to', 'Please enter one or more e-mail addresses if replies to this e-mail should not be sent to sender address. Separate multiple addresses with comma.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSubject'] = array('Subject', 'Please enter the subject of confirmation mail. If you do not enter a subject, the probability increases that the e-mail is identified as SPAM.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailText'] = array('Text of confirmation mail', 'Please enter the text of confirmation mail. You can use standard insert tags and tags like form::NAME_OF_FORMFIELD .');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailTemplate'] = array('HTML-template for confirmation e-mail', 'If the confirmation e-mail should be sent as HTML mail, please choose your HTML-template from the file system.');
$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAttachments'] = array('Add attachments to confirmation e-mail', 'You can select files that will be sent as attachments to the confirmation e-mail.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAttachments'] = array('Attachments', 'Pleae choose files to attach.');
$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMailAlternate'] = array('Send alternate confirmation via e-mail', 'If you choose this option, an alternate confirmation will be sent via e-mail to the sender of the form.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateCondition'] = array('Send condition', 'If you want the alternate e-mail only sent under a specific condition, please enter a condition based on a specific question, e.g. \'{{q::gender}}\' == \'male\'.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateRecipient'] = array('Alternate recipient', 'Please enter one or more recipient e-mail addresses for an alternate confirmation mail. Separate multiple addresses with comma.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSender'] = array('Alternate sender', 'Please enter e-mail address to be used as sender for an alternate confirmation mail.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateReplyto'] = array('Reply to', 'Please enter one or more e-mail addresses if replies to this e-mail should not be sent to sender address. Separate multiple addresses with comma.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSubject'] = array('Subject', 'Please enter the subject of the alternate confirmation mail. If you do not enter a subject, the probability increases that the e-mail is identified as SPAM.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateText'] = array('Text of alternate confirmation e-mail', 'Please enter the text of the alternate confirmation e-mail. You can use standard insert tags and tags like form::NAME_OF_FORMFIELD .');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateTemplate'] = array('HTML-template for alternate confirmation mail', 'If the alternate confirmation mail should be sent as HTML mail, please choose your HTML-template from the file system.');
$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAlternateAttachments'] = array('Add attachments to alternate confirmation e-mail', 'You can select files that will be sent as attachments to the alternate confirmation e-mail.');
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateAttachments'] = array('Alternate attachments', 'Pleae choose files to attach.');


$GLOBALS['TL_LANG']['tl_survey']['addFormattedMailAttachments'] = array('Add attachments to e-mail', 'You can select files that will be sent as attachments to the e-mail.');
$GLOBALS['TL_LANG']['tl_survey']['formattedMailAttachments'] = array('Attachments', 'Pleae choose files to attach.');
$GLOBALS['TL_LANG']['tl_survey']['sendFormattedMail'] = array('Send form data via e-mail (formatted text or html)', 'The text of mail can be defined using insert tags. Mail can be sent as HTML mail.');
$GLOBALS['TL_LANG']['tl_survey']['formattedMailText'] = array('Text of mail', 'Please enter the text of mail. You can use standard insert tags and tags like form::NAME_OF_FORMFIELD .');
$GLOBALS['TL_LANG']['tl_survey']['formattedMailTemplate'] = array('HTML-template for mail', 'If mail should be sent as HTML mail, please choose your HTML-template from file system.');

$GLOBALS['TL_LANG']['tl_survey']['useResultCategories'] = [
    'Use answer cateories',
    'Activate this option to enable categories for (multiple choice) answers.'
];
$GLOBALS['TL_LANG']['tl_survey']['resultCategories'] = [
    'Answer categories',
    'Create answer categories',
];
$GLOBALS['TL_LANG']['tl_survey']['resultCategories_'] = [
    'id' => ['ID', 'The internal id for the categorie. Read only.'],
    'category' => ['Title', 'The category title.']
];

/*
* Legends
*/
$GLOBALS['TL_LANG']['tl_survey']['head_legend'] = 'Head settings';
$GLOBALS['TL_LANG']['tl_survey']['title_legend'] = 'Title and description';
$GLOBALS['TL_LANG']['tl_survey']['activation_legend'] = 'Activation';
$GLOBALS['TL_LANG']['tl_survey']['access_legend'] = 'Access';
$GLOBALS['TL_LANG']['tl_survey']['texts_legend'] = 'Statements';
$GLOBALS['TL_LANG']['tl_survey']['misc_legend'] = 'General settings';
$GLOBALS['TL_LANG']['tl_survey']['sendformattedmail_legend'] = 'Send via e-mail';
$GLOBALS['TL_LANG']['tl_survey']['sendconfirmationmail_legend'] = 'Send confirmation via e-mail';
