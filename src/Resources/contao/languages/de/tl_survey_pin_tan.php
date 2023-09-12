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

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'] = ['TAN-Codes erzeugen', 'TAN-Codes erzeugen'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'] = 'TAN-Codes exportieren';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tans'] = 'TAN-Codes';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['sort'] = 'Sortierung';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['url'] = 'URL zur Umfrage';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['create'] = 'Erzeugen';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['export'] = 'Exportieren';
// list labels
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'] = 'Die TAN wurde bereits verwendet';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'] = 'Die TAN ist noch nicht verwendet worden';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['key'] = 'TAN wurde generiert am:';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invited'] = 'Mitglied wurde zur Umfrage eingeladen am:';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['reminded'] = 'Mitglied wurde an die Umfrage erinnert am:';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['reminder'] = '. Erinnerung am: ';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['not_yet'] = 'noch nicht';

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'] = ['Anzahl der TAN-Codes', 'Bitte geben Sie die Anzahl der TAN-Codes ein, die Sie erzeugen möchten.'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['memberGroupId'] = [
    'für Mitglieder der Gruppe',
    'Wählen Sie hier eine Mitgliedergruppe oder &raquo;alle Mitglieder&laquo; für alle Mitglieder.',
    'keine Gruppe (alle aktiven Mitglieder)',
];
// only for the xls exporter
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['member_id'] = ['Mitglied', ''];

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'] = ['TAN', 'Transaktionnummer (TAN)'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tstamp'] = ['Erstellungsdatum', 'Erstellungsdatum'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'] = ['TAN wurde benutzt', 'Die TAN wurde bereits von einem Teilnehmer benutzt.'];
// new TAN generation
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['success'] = 'Es wurden %s TANs generiert. %s TANs sind unbenutzt und wurden beibehalten.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['error'] = 'Es wurden keine TANs generiert, weil für diese Umfrage keine Mitglieder ermittelt werden konnten. Bitte prüfen Sie, ob die betreffenden Mitglieder aktiviert bzw. nicht gesperrt sind. Für gesperrte und deaktivierte Mitglieder werden keine TANs generiert.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_empty'] = 'Gruppe &raquo;%s&laquo; ist aktiviert, enthält jedoch keine Mitglieder. Für diese Gruppe wurden keine TANs generiert.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_disabled'] = 'Gruppe &raquo;%s&laquo; ist deaktiviert. Für Mitglieder dieser Gruppe wurden keine TANs generiert.';

$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite'] = ['Einladen', 'Alle Mitglieder zur Umfrage einladen. Es werden alle Mitglieder zur Umfrage eingeladen, die ihre Umfrage noch nicht &raquo;begonnen&laquo; oder noch nicht &raquo;beendet&laquo; haben.'];
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind'] = ['Erinnern', 'Alle Mitglieder an die Umfrage erinnern. Es werden alle Mitglieder an die Umfrage erinnert, die ihre Umfrage noch nicht &raquo;begonnen&laquo; haben.'];

// partcipant invite view
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_note_template'] = <<< 'EOT'
    <h1>Sie möchten für die Umfrage &raquo;%s&laquo; eine Einladung an alle teilnehmenden Personen versenden.</h1>
    <p>%s</p>
    <p>%s</p>
    <p>%s</p>
    <h1>Es werden %s Mitglieder eingeladen.%s</h1>
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_text'] = <<< 'EOT'
    Alle Teilnehmenden Personen erhalten eine Einladungsmail. Sie haben dafür die Nachricht
    <p>&raquo;<strong>%s</strong>&laquo;</p>
    im Notification Center festgelegt. Die Einladung enthält einen personalisierten Link, mit dem die eingeladene Person die Umfrage aufrufen kann.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_warn'] = <<< 'EOT'
    Je nach Größe der Zielgruppe kann das Versenden der Einladungen einige Zeit in Anspruch nehmen.
    Stellen Sie daher bitte sicher, dass Sie über Ihr System Massenmails versenden dürfen, damit der Hoster
    nicht versehentlich Ihr Konto wegen des Verdachts auf einen Spam-Versand sperrt.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_hint'] = <<<'EOT'
    Es werden nur Personen eingeladen,
    <ul>
    <li>die ihre Umfrage &raquo;<strong>noch nicht begonnen</strong>&laquo; haben.
    (Personen die ihre Umfrage &raquo;<strong>begonnen</strong>&laquo;
    oder bereits &raquo;<strong>abgeschlossen</strong>&laquo; haben, werden nicht eingeladen.)</li>
    <li>die bisher noch nicht eingeladen wurden und</li>
    </ul>
    EOT;
// nobody to invite
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_none'] = ['keine', ' Es wurden bereits alle Mitglieder zu dieser Umfrage eingeladen.'];

// buttons
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_invitation_send'] = 'Jetzt einladen';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_invitation_cancel'] = 'Abbrechen';

// member invite messages
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_result_template'] = 'Es wurden %s Einladungen versandt, %s Einladungen wurden übergangen.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite_no_invitation_available'] = 'Die in der Umfrage angegebene Notification für die Einladung ist nicht vorhanden.';

// partcipant remind view
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_note_template'] = <<< 'EOT'
    <h1>Sie möchten für die Umfrage &raquo;%s&laquo; eine Erinnerung an alle teilnehmenden Personen versenden.</h1>
    <p>%s</p>
    <p>%s</p>
    <p>%s</p>
    <h1>Es werden %s Mitglieder erinnert.%s</h1>
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_text'] = <<< 'EOT'
    Alle Teilnehmenden Personen erhalten eine Erinnerungssmail. Sie haben dafür die Nachricht
    <p>&raquo;<strong>%s</strong>&laquo;</p>
    im Notification Center festgelegt. Die Erinnerung enthält einen personalisierten Link, mit dem die erinnerte Person die Umfrage aufrufen kann.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_warn'] = <<< 'EOT'
    Je nach Größe der Zielgruppe kann das Versenden der Erinnerungen einige Zeit in Anspruch nehmen.
    Stellen Sie daher bitte sicher, dass Sie über Ihr System Massenmails versenden dürfen, damit der Hoster
    nicht versehentlich Ihr Konto wegen des Verdachts auf einen Spam-Versand sperrt.
    EOT;
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_hint'] = <<<'EOT'
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
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_reminder_send'] = 'Jetzt erinnern';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['button_reminder_cancel'] = 'Abbrechen';

// member remind messages
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_result_template'] = 'Es wurden %s Erinnerungen versandt, %s Erinnerungen wurden übergangen.';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind_no_reminder_available'] = 'Die in der Umfrage angegebene Notification für die Erinnerung ist nicht vorhanden.';

// export label
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['surveyPage'] = $GLOBALS['TL_LANG']['tl_survey']['surveyPage'];
