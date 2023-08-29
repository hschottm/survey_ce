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

$GLOBALS['TL_LANG']['tl_survey_participant']['show']['0'] = 'Teilnehmerdatendetails';
$GLOBALS['TL_LANG']['tl_survey_participant']['show']['1'] = 'Details des Teilnehmers ID %s anzeigen';
$GLOBALS['TL_LANG']['tl_survey_participant']['delete']['0'] = 'Teilnehmerdaten löschen';
$GLOBALS['TL_LANG']['tl_survey_participant']['delete']['1'] = 'Teilnehmerdaten ID %s löschen';
$GLOBALS['TL_LANG']['tl_survey_participant']['tstamp']['0'] = 'Änderungsdatum';
$GLOBALS['TL_LANG']['tl_survey_participant']['tstamp']['1'] = 'Datum der letzten Änderung des Teilnehmers';
$GLOBALS['TL_LANG']['tl_survey_participant']['lastpage']['0'] = 'Letzte Seite';
$GLOBALS['TL_LANG']['tl_survey_participant']['lastpage']['1'] = 'Letzte Seite, die der Teilnehmer angesehen hat';
$GLOBALS['TL_LANG']['tl_survey_participant']['finished'] = 'beendet';
$GLOBALS['TL_LANG']['tl_survey_participant']['running'] = 'begonnen';

$GLOBALS['TL_LANG']['tl_survey_participant']['exportraw']   = ['Detaillierter Export', 'Export mit allen Teilnehmer-Antworten, filter- und sortierbar'];
$GLOBALS['TL_LANG']['tl_survey_participant']['invite']      = ['Einladen', 'Alle Mitglieder zur Umfrage einladen. Es werden alle Mitglieder zur Umfrage eingeladen, die ihre Umfrage noch nicht &raquo;begonnen&laquo; oder noch nicht &raquo;beendet&laquo; haben.'];
$GLOBALS['TL_LANG']['tl_survey_participant']['remind']      = ['Erinnern', 'Alle Mitglieder an die Umfrage erinnern. Es werden alle Mitglieder an die Umfrage erinnert, die ihre Umfrage noch nicht &raquo;begonnen&laquo; haben.'];

$GLOBALS['TL_LANG']['tl_survey_participant']['note_template'] =<<< EOT
<h1>Sie möchten für die Umfrage &raquo;%s&laquo; an alle teilnehmenden Personen eine Einladung versenden.</h1>
<p>%s</p>
<p style='color:#F47C00;'>%s</p>
<p style='color:#F47C00;'>%s</p>
<h1>Es werden %s Mitglieder eingeladen.</h1>
EOT;
$GLOBALS['TL_LANG']['tl_survey_participant']['invite_text'] =<<< EOT
Alle Teilnehmenden Personen erhalten eine Einladungsmail. Sie haben dafür die Nachricht &raquo;<strong>%s</strong>&laquo; im Notification Center festgelegt. Die Einladung enthält einen personalisierten Link, mit dem die eingeladene Person die Umfrage aufrufen kann.
EOT;
$GLOBALS['TL_LANG']['tl_survey_participant']['invite_warn'] =<<< EOT
Je nach Größe der Zielgruppe kann das Versenden der Einladungen einige Zeit in Anspruch nehmen.
Stellen Sie auch bitte sicher, dass Sie über Ihr System Massenmails versenden dürfen, damit der Hoster
nicht versehentlich Ihr Konto wegen des Verdachts auf einen Spam-Versand sperrt.
EOT;
$GLOBALS['TL_LANG']['tl_survey_participant']['invite_hint'] =<<< EOT
Es werden nur Personen eingeladen, die ihre Umfrage &raquo;<strong style='color:#F47C00;'>noch
nicht begonnen</strong>&laquo; haben. Personen die ihre Umfrage
bereits &raquo;<strong style='color:#F47C00;'>abgeschlossen</strong>&laquo;
oder &raquo;<strong style='color:#F47C00;'>begonnen</strong>&laquo; haben, werden nicht eingeladen.
EOT;
