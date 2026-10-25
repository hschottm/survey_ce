<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
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
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titel und Beschreibung';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Einleitender Text';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Template-Einstellungen';

$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Weiterleitung nach dieser Seite';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'] = ['Weiterleitungsregeln', 'Regeln werden beim Fortfahren nach dieser Seite ausgewertet.'];
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor'] = [
    'help' => 'Die Regeln werden von oben nach unten geprüft. Die erste passende Regel bestimmt die nächste Seite. Innerhalb einer Regel müssen alle Bedingungen erfüllt sein. Fehlende Antworten erfüllen keine Bedingung.',
    'fallback' => 'Wenn keine Regel zutrifft, folgt die nächste Seite in der Reihenfolge. Eine Regel „Immer“ kann am Ende ein anderes Standardziel festlegen.',
    'searchQuestions' => 'Fragenauswahl filtern', 'searchHelp' => 'Nach Fragentitel oder ID suchen',
    'rule' => 'Regel', 'when' => 'Wenn', 'all' => 'Alle folgenden Bedingungen erfüllt sind (UND)', 'always' => 'Immer',
    'question' => 'Frage', 'operator' => 'Vergleich', 'answer' => 'Antwort', 'target' => 'Dann weiter zu',
    'chooseQuestion' => 'Bitte eine Frage wählen', 'chooseAnswer' => 'Bitte eine Antwort wählen', 'choosePage' => 'Bitte eine Seite wählen',
    'addRule' => 'Weitere Regel hinzufügen', 'removeRule' => 'Regel entfernen',
    'addCondition' => 'Weitere UND-Bedingung hinzufügen', 'removeCondition' => 'Bedingung entfernen',
    'up' => 'Nach oben', 'down' => 'Nach unten',
    'contains' => 'enthält Antwort', 'notContains' => 'enthält Antwort nicht',
    'operators' => ['=' => 'ist gleich', '!=' => 'ist ungleich', '>' => 'ist größer als', '<' => 'ist kleiner als', '>=' => 'ist größer oder gleich', '<=' => 'ist kleiner oder gleich'],
    'invalid' => 'Bitte alle Weiterleitungsregeln prüfen: Frage, Vergleich, Antwort und Zielseite müssen gültig sein. Das Ziel muss eine andere Seite derselben Umfrage sein. Es sind höchstens 100 Regeln mit je 100 Bedingungen erlaubt.',
    'legacy' => 'Diese bestehende Regel enthält eine nicht mehr verfügbare oder hier noch nicht unterstützte Bedingung. Sie bleibt unverändert erhalten. Sie können die Regel verschieben oder entfernen und durch eine neue Regel ersetzen.',
    'legacyValue' => 'Bestehender Wert:',
    'unreachable' => 'Diese Regel trifft immer zu. Darunter liegende Regeln werden nicht mehr erreicht.',
    'javascript' => 'Zum Bearbeiten der Regeln muss JavaScript aktiviert sein. Bestehende Regeln bleiben beim Speichern unverändert.',
];

$GLOBALS['TL_LANG']['tl_survey_page']['children'] = ['Fragen bearbeiten', 'Fragen bearbeiten (ID %s)'];
