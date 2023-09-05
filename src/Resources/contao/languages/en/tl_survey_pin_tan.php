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

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'] = 'Create TAN codes';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'] = 'Export TAN codes';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tans'] = 'TAN codes';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['sort'] = 'Sort order';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['url'] = 'URL to start survey';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['create'] = 'Create';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['export'] = 'Export';
// list labels
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'] = 'The TAN code is in use';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'] = 'The TAN code is not in use';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['key'] = 'TAN generated at:';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'] = ['Number of TAN codes', 'Please enter the number of the TAN codes you want to create.'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['memberGroupId'] = [
    'for members of the group',
    'Select here a member group or &raquo;all members&laquo; for all members.',
    'no group (all active members)',
];
// only for the xls exporter
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['member_id'] = ['Member', ''];

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invited'] = 'Member invited at:';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['reminded'] = 'Member reminded with';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['reminder'] = '. reminder ';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['not_yet'] = 'not yet';

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'] = ['TAN', 'Transaction number (TAN code)'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tstamp'] = ['Created', 'Created'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'] = ['TAN is used', 'The TAN has been used by a survey participant'];

// new TAN generation
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['success'] = '%s TANs have been generated. %s TANs are unused and have been retained.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['error']   = 'No TANs were generated because no members could be identified for this survey. Please check whether the members in question are activated or not blocked. No TANs are generated for blocked and deactivated members.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_empty'] = 'Group &raquo;%s&laquo; is activated but contains no members. No TANs have been generated for this group.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_disabled'] = 'Group &raquo;%s&laquo; is deactivated. No TANs were generated for members of this group.';

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite'] = ['Invite', 'Invite all members to the survey. All members who have not &raquo;started&laquo; or have not &raquo;finished&laquo; their survey will be invited to the survey.'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind'] = ['Remind', 'Remind all members about the survey. All members who have not yet &raquo;started&laquo; their survey will be reminded.'];

// partcipant invite view
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_note_template'] = <<< 'EOT'
    <h1>You want to send an invitation for the survey &raquo;%s&laquo; to all people who participate?</h1>
    <p>%s</p>
    <p>%s</p>
    <p>%s</p>
    <h1>There will be %s members invited.%s</h1>
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_text'] = <<< 'EOT'
    All participating people will receive an invitation email. You have set the message
    <p>&raquo;<strong>%s</strong>&laquo;</p>
    in the Notification Center. The invitation contains a personalized link that the invited person can use to access the survey.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_warn'] = <<< 'EOT'
    Depending on the size of the target group, sending the invitations may take some time.
    Therefore, please make sure that you are allowed to send mass mails via your system, so that the hoster
    does not accidentally block your account because of suspicion of sending spam.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_hint'] = <<<EOT
    Only the following persons are invited,
    <ul>
    <li>who have &raquo;<strong>not yet started</strong>&laquo;  their survey.
    (People who &raquo;<strong>have started</strong>&laquo; or &raquo;<strong>already completed</strong>&laquo; their survey will not be invited).</li>
    <li>who have not yet been invited</li>
    </ul>
    EOT;
// nobody to invite
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_none'] = ['no', ' All members have already been invited to participate in this survey.'];

// buttons
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_invitation_send']   = 'Invite now';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_invitation_cancel'] = 'Cancel';

// member invite messages
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_result_template'] = '%s invitations were sent, %s invitations were skipped.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_no_invitation_available'] = 'The notification for the invitation specified in the survey is not available.';



// partcipant remind view
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_note_template'] = <<< 'EOT'
    <h1>You want to send a reminder for the survey &raquo;%s&laquo; to everyone who participates?</h1>
    <p>%s</p>
    <p>%s</p>
    <p>%s</p>
    <h1>%s members are reminded.%s</h1>
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_text'] = <<< 'EOT'
    All participating people will receive a reminder email. For this you have set the message
    <p>&raquo;<strong>%s</strong>&laquo;</p>
    in the Notification Center for this. The reminder contains a personalized link that the reminded person can use to access the survey.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_warn'] = <<< 'EOT'
    Depending on the size of the target group, sending the invitations may take some time.
    Therefore, please make sure that you are allowed to send mass mails via your system, so that the hoster
    does not accidentally block your account because of suspicion of sending spam.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_hint'] = <<<EOT
    Es werden nur Personen erinnert,
    <ul>
    <li>die ihre Umfrage &raquo;<strong>noch nicht begonnen</strong>&laquo; haben.
    (Personen die ihre Umfrage &raquo;<strong>begonnen</strong>&laquo;
    oder bereits &raquo;<strong>abgeschlossen</strong>&laquo; haben, werden nicht erinnert.)</li>
    </ul>
    EOT;
// nobody to invite
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_none'] = ['keine', ' Die Mitglieder dieser Umfrage wurden bereits erinnert.'];

// buttons
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_reminder_send']   = 'Jetzt erinnern';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_reminder_cancel'] = 'Abbrechen';

// member invite messages
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_result_template'] = 'Es wurden %s Erinnerungen versandt, %s Erinnerungen wurden übergangen.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_no_invitation_available'] = 'Die in der Umfrage angegebene Notification für die Erinnerung ist nicht vorhanden.';
