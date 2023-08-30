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
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'] = 'Die TAN wurde bereits verwendet';
$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'] = 'Die TAN ist noch nicht verwendet worden';
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
