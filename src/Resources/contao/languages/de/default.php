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

$GLOBALS['TL_LANG']['CTE']['survey']['0'] = 'Umfrage';
$GLOBALS['TL_LANG']['CTE']['survey']['1'] = 'Verwenden Sie diese Option, um eine Umfrage in den Artikel einzubinden.';
$GLOBALS['TL_LANG']['MSC']['deleteAll'] = 'Alle Teilnehmerdaten löschen';
$GLOBALS['TL_LANG']['MSC']['survey_next'] = 'Weiter';
$GLOBALS['TL_LANG']['MSC']['page_x_of_y'] = 'Seite %s von %s';
$GLOBALS['TL_LANG']['MSC']['survey_prev'] = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['survey_start'] = 'Umfrage starten';
$GLOBALS['TL_LANG']['MSC']['survey_finish'] = 'Umfrage beenden';
$GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'] = 'Vielen Dank, dass Sie an der Umfrage teilgenommen haben.';
$GLOBALS['TL_LANG']['MSC']['cancel_survey'] = 'Diese Umfrage beenden';
$GLOBALS['TL_LANG']['ERR']['survey_already_finished'] = 'Sie haben die Umfrage bereits ein Mal durchgeführt.';
$GLOBALS['TL_LANG']['ERR']['survey_please_enter_tan'] = 'Sie müssen einen TAN-Code eingeben, um die Umfrage zu starten.';
$GLOBALS['TL_LANG']['ERR']['survey_wrong_tan'] = 'Der eingegebene TAN-Code ist nicht gültig für diese Umfrage. Bitte geben Sie eine gültige TAN ein.';
$GLOBALS['TL_LANG']['ERR']['survey_no_member'] = 'Sie müssen als Mitglied angemeldet sein, um an dieser Umfrage teilzunehmen.';
$GLOBALS['TL_LANG']['ERR']['survey_no_allowed_member'] = 'Sie haben nicht die Berechtigung, an dieser Umfrage teilzunehmen.';
$GLOBALS['TL_LANG']['ERR']['sumnotexact'] = 'Die Summe der angegebenen Werte der Frage mit dem Titel "%s" beträgt nicht genau %s';
$GLOBALS['TL_LANG']['ERR']['sumnotmax'] = 'Die Summe der angegebenen Werte der Frage mit dem Titel "%s" überschreitet %s';
$GLOBALS['TL_LANG']['ERR']['selectoption'] = 'Bitte wählen Sie eine Option aus';
$GLOBALS['TL_LANG']['ERR']['mandatory_constantsum'] = 'Bitte füllen Sie die Frage mit dem Titel "%s" vollständig aus.';
$GLOBALS['TL_LANG']['ERR']['mandatory_matrix'] = 'Bitte wählen Sie in jeder Zeile der Frage mit dem Titel "%s" mindestens eine Option aus.';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'] = 'Bitte wählen Sie genau eine Antwort der Frage mit dem Titel "%s" aus.';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_mr'] = 'Bitte wählen Sie mindestens eine Antwort der Frage mit dem Titel "%s" aus.';
$GLOBALS['TL_LANG']['ERR']['missing_other_value'] = 'Sie haben eine zusätzliche Antwort ausgewählt und müssen einen Text dafür eintragen.';
$GLOBALS['TL_LANG']['ERR']['lower_bound'] = 'Der Wert (%s) für "%s" ist kleiner als erlaubt (%s).';
$GLOBALS['TL_LANG']['ERR']['upper_bound'] = 'Der Wert (%s) für "%s" ist größer als erlaubt (%s).';
