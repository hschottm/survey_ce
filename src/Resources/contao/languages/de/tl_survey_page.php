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

$GLOBALS['TL_LANG']['tl_survey_page']['title']['0'] = 'Titel';
$GLOBALS['TL_LANG']['tl_survey_page']['title']['1'] = 'Bitte geben Sie den Titel der Seite ein.';
$GLOBALS['TL_LANG']['tl_survey_page']['description']['0'] = 'Beschreibung';
$GLOBALS['TL_LANG']['tl_survey_page']['description']['1'] = 'Bitte geben Sie eine Beschreibung der Seite ein.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp']['0'] = 'Zuletzt geändert';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp']['1'] = 'Datum und Uhrzeit der letzten Änderung der Seite.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction']['0'] = 'Einleitender Text';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction']['1'] = 'Bitte geben Sie einen einleitenden Text für die Seite ein. Der Text erscheint am Anfang der Seite vor der ersten Frage.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template']['0'] = 'Seitentemplate';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template']['1'] = 'Hier können Sie ein Seitentemplate auswählen.';
$GLOBALS['TL_LANG']['tl_survey_page']['new']['0'] = 'Neue Seite';
$GLOBALS['TL_LANG']['tl_survey_page']['new']['1'] = 'Eine neue Seite anlegen';
$GLOBALS['TL_LANG']['tl_survey_page']['show']['0'] = 'Seitendetails';
$GLOBALS['TL_LANG']['tl_survey_page']['show']['1'] = 'Details der Seite ID %s anzeigen';
$GLOBALS['TL_LANG']['tl_survey_page']['cut']['0'] = 'Seite verschieben';
$GLOBALS['TL_LANG']['tl_survey_page']['cut']['1'] = 'Seite ID %s verschieben';
$GLOBALS['TL_LANG']['tl_survey_page']['edit']['0'] = 'Seite bearbeiten';
$GLOBALS['TL_LANG']['tl_survey_page']['edit']['1'] = 'Seite ID %s bearbeiten';
$GLOBALS['TL_LANG']['tl_survey_page']['copy']['0'] = 'Seite duplizieren';
$GLOBALS['TL_LANG']['tl_survey_page']['copy']['1'] = 'Seite ID %s duplizieren';
$GLOBALS['TL_LANG']['tl_survey_page']['delete']['0'] = 'Seite löschen';
$GLOBALS['TL_LANG']['tl_survey_page']['delete']['1'] = 'Seite ID %s löschen';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Seite';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'] = ['Sprungbedingungen', 'Wenn Sie nicht mit der nächsten Seite fortfahren wollen, können Sie hier Sprungbedingungen definieren, um mit einer anderen Seite fortzufahren.'];
$GLOBALS['TL_LANG']['tl_survey_page']['type'] = ['Seitentyp', 'Bitte wählen Sie den Typ der Seite.'];
$GLOBALS['TL_LANG']['tl_survey_page']['useCustomNextButtonTitle'] = [
    '"Weiter"-Text anpassen',
    'Wählen Sie diese Option, um der "Weiter"-Schaltfläche der vorrigen Seite einen benutzerdefinierten Text zuzuweisen.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['customNextButtonTitle'] = [
    '"Weiter"-Text',
    'Geben Sie einen Text für die "Weiter"-Schaltfläche der vorrigen Seite an.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['hideBackButton'] = [
    '"Zurück" ausblenden',
    'Wählen Sie diese Option, um die "Zurück"-Schaltfläche auf dieser Seite auszublenden.',
];
$GLOBALS['TL_LANG']['tl_survey_page']['markSurveyAsFinished'] = [
    'Umfrage als beendet markieren',
    'Wählen Sie diese Option, um die Umfrage beim erreichen dieser Seite als beendet zu markieren.',
];

/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_survey_page']['type_legend'] = 'Seitentyp';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titel und Beschreibung';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Einleitender Text';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Sprungbedingungen';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_survey_page']['config_legend'] = 'Konfiguration';

/*
 * Page types
 */
$GLOBALS['TL_LANG']['tl_survey_page']['type']['default'] = 'Fragenseite';
$GLOBALS['TL_LANG']['tl_survey_page']['type']['result'] = 'Ergebnisseite';
