<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_question']['title']['0'] = 'Titel';
$GLOBALS['TL_LANG']['tl_survey_question']['title']['1'] = 'Bitte geben Sie den Titel der Frage ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['alias']['0'] = "Alias";
$GLOBALS['TL_LANG']['tl_survey_question']['alias']['1'] = "Der Fragenalias ist eine eindeutige Referenz, die anstelle der numerischen Fragen-ID aufgerufen werden kann.";
$GLOBALS['TL_LANG']['tl_survey_question']['author']['0'] = 'Autor';
$GLOBALS['TL_LANG']['tl_survey_question']['author']['1'] = 'Bitte geben Sie den Namen des Autors ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['questiontype']['0'] = 'Fragentyp';
$GLOBALS['TL_LANG']['tl_survey_question']['questiontype']['1'] = 'Bitte wählen Sie den Fragentyp aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['description']['0'] = 'Beschreibung';
$GLOBALS['TL_LANG']['tl_survey_question']['description']['1'] = 'Bitte geben Sie eine Beschreibung für die Frage ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['question']['0'] = 'Fragentext';
$GLOBALS['TL_LANG']['tl_survey_question']['question']['1'] = 'Bitte geben Sie einen Fragentext ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['language']['0'] = 'Sprache';
$GLOBALS['TL_LANG']['tl_survey_question']['language']['1'] = 'Bitte wählen Sie die Sprache der Frage.';
$GLOBALS['TL_LANG']['tl_survey_question']['obligatory']['0'] = 'Verpflichtend';
$GLOBALS['TL_LANG']['tl_survey_question']['obligatory']['1'] = 'Wenn Sie diese Option wählen, muss die Frage in einer Umfrage beantwortet werden.';
$GLOBALS['TL_LANG']['tl_survey_question']['help']['0'] = 'Hilfetext';
$GLOBALS['TL_LANG']['tl_survey_question']['help']['1'] = 'Bitte geben Sie einen Hilfetext ein, der zusätzlich zum Fragentitel angezeigt werden soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['introduction']['0'] = 'Einleitung';
$GLOBALS['TL_LANG']['tl_survey_question']['introduction']['1'] = 'Bitte geben Sie einen einleitenden Text ein, der zu Beginn der Seite angezeigt wird.';
$GLOBALS['TL_LANG']['tl_survey_question']['lower_bound']['0'] = 'Wertebereich von';
$GLOBALS['TL_LANG']['tl_survey_question']['lower_bound']['1'] = 'Bitte geben Sie die untere Schranke des Wertebereiches ein, wenn dieser überprüft werden soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['upper_bound']['0'] = 'Wertebereich bis';
$GLOBALS['TL_LANG']['tl_survey_question']['upper_bound']['1'] = 'Bitte geben Sie die obere Schranke des Wertebereiches ein, wenn dieser überprüft werden soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['choices']['0'] = 'Antworten';
$GLOBALS['TL_LANG']['tl_survey_question']['choices']['1'] = 'Benutzen Sie die Schaltflächen, um Antworten zu kopieren, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Antworten verändern!';
$GLOBALS['TL_LANG']['tl_survey_question']['choices_'] = [
    'choice' => [
        'Antwort'
    ],
    'category' => [
        'Kategorie'
    ],
];

$GLOBALS['TL_LANG']['tl_survey_question']['hidetitle']['0'] = 'Fragentitel nicht anzeigen';
$GLOBALS['TL_LANG']['tl_survey_question']['hidetitle']['1'] = 'Zeigt den Titel der Frage während der Umfrage nicht an.';
$GLOBALS['TL_LANG']['tl_survey_question']['addother']['0'] = 'Andere Antwort erlauben';
$GLOBALS['TL_LANG']['tl_survey_question']['addother']['1'] = 'Wenn Sie diese Option wählen, wird als letzte Antwortoption ein Textfeld für eine freie Antwort angeboten.';
$GLOBALS['TL_LANG']['tl_survey_question']['addscale']['0'] = 'Vordefinierte Skala hinzufügen';
$GLOBALS['TL_LANG']['tl_survey_question']['addscale']['1'] = 'Wählen Sie eine Skala aus einer Liste von vordefinierten Skalen aus und fügen Sie diese Ihrer Frage hinzu.';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['0'] = 'Darstellung der Antworten';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['1'] = 'Bitte wählen Sie eine Darstellungsform für die Antworten der Frage aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['vertical'] = 'Vertikal (Antworten untereinander)';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['horizontal'] = 'Horizontal (Antworten nebeneinander)';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['select'] = 'Dropdown-Feld';
$GLOBALS['TL_LANG']['tl_survey_question']['othertitle']['0'] = 'Titel der anderen Antwort';
$GLOBALS['TL_LANG']['tl_survey_question']['othertitle']['1'] = 'Geben Sie einen Titel für die zusätzliche andere Antwort ein. Der Text wird vor dem Antwortfeld angezeigt.';
$GLOBALS['TL_LANG']['tl_survey_question']['scale']['0'] = 'Skala';
$GLOBALS['TL_LANG']['tl_survey_question']['scale']['1'] = 'Wählen Sie eine Skala aus einer Liste der vordefinierten Skalen aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['save_add_scale'] = 'Skala hinzufügen';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_subtype']['0'] = 'Untertyp';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_subtype']['1'] = 'Bitte wählen Sie den Untertyp für die offene Frage aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textbefore']['0'] = 'Beschriftung vor Textfeld';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textbefore']['1'] = 'Bitte geben Sie eine Beschriftung ein, die vor dem Textfeld erscheinen soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textafter']['0'] = 'Beschriftung nach Textfeld';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textafter']['1'] = 'Bitte geben Sie eine Beschriftung ein, die nach dem Textfeld erscheinen soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textinside']['0'] = 'Vorbelegung';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textinside']['1'] = 'Bitte geben Sie einen Text ein, mit dem das Textfeld vorbelegt werden soll.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_rows']['0'] = 'Zeilen';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_rows']['1'] = 'Bitte geben Sie die Anzahl der Zeilen für das mehrzeilige Textfeld an.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_cols']['0'] = 'Spalten';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_cols']['1'] = 'Bitte geben Sie die Anzahl der Spalten für das mehrzeilige Textfeld an.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_width']['0'] = 'Breite';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_width']['1'] = 'Bitte geben Sie die Breite des Textfeldes in Zeichen an.';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_maxlen']['0'] = 'Maximale Länge';
$GLOBALS['TL_LANG']['tl_survey_question']['openended_maxlen']['1'] = 'Bitte geben Sie die maximale Anzahl von Zeichen für dieses Textfeld an, wenn Sie auf eine Eingabebeschränkung bestehen.';
$GLOBALS['TL_LANG']['tl_survey_question']['multiplechoice_subtype']['0'] = 'Untertyp';
$GLOBALS['TL_LANG']['tl_survey_question']['multiplechoice_subtype']['1'] = 'Bitte wählen Sie den Untertyp für die Multiple Choice Frage aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_subtype']['0'] = 'Untertyp';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_subtype']['1'] = 'Bitte wählen Sie den Untertyp für die Matrixfrage aus.';
$GLOBALS['TL_LANG']['tl_survey_question']['matrixrows']['0'] = 'Zeilen';
$GLOBALS['TL_LANG']['tl_survey_question']['matrixrows']['1'] = 'Benutzen Sie die Schaltflächen, um Zeilen zu kopieren, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Zeilen verändern!';
$GLOBALS['TL_LANG']['tl_survey_question']['matrixcolumns']['0'] = 'Spalten';
$GLOBALS['TL_LANG']['tl_survey_question']['matrixcolumns']['1'] = 'Benutzen Sie die Schaltflächen, um Spalten zu kopieren, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Spalten verändern!';
$GLOBALS['TL_LANG']['tl_survey_question']['addneutralcolumn']['0'] = 'Neutrale Spalte erlauben';
$GLOBALS['TL_LANG']['tl_survey_question']['addneutralcolumn']['1'] = 'Wenn Sie diese Option wählen, wird als letzte Spalte eine neutrale Spalte (z.B. keine Angabe, weiß nicht etc.) angeboten.';
$GLOBALS['TL_LANG']['tl_survey_question']['neutralcolumn']['0'] = 'Neutrale Spalte';
$GLOBALS['TL_LANG']['tl_survey_question']['neutralcolumn']['1'] = 'Bitte geben Sie einen Text für die neutrale Spalte ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['addbipolar']['0'] = 'Entgegengesetzte Pole anzeigen';
$GLOBALS['TL_LANG']['tl_survey_question']['addbipolar']['1'] = 'Wenn Sie diese Option wählen, können Sie entgegengesetzte Pole definieren, die in der Matrixfrage angezeigt werden.';
$GLOBALS['TL_LANG']['tl_survey_question']['adjective1']['0'] = 'Linker Pol';
$GLOBALS['TL_LANG']['tl_survey_question']['adjective1']['1'] = 'Bitte geben Sie einen Text für den linken Pol ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['adjective2']['0'] = 'Rechter Pol';
$GLOBALS['TL_LANG']['tl_survey_question']['adjective2']['1'] = 'Bitte geben Sie einen Text für den rechten Pol ein.';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['0'] = 'Position der Pole';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['1'] = 'Bitte wählen Sie aus, wo die entgegengesetzten Pole in der Matrixfrage angezeigt werden sollen.';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['top'] = 'Über den Spaltenbezeichnern';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['aside'] = 'Links und rechts der Spalten';
$GLOBALS['TL_LANG']['tl_survey_question']['new']['0'] = 'Neue Frage';
$GLOBALS['TL_LANG']['tl_survey_question']['new']['1'] = 'Eine neue Frage anlegen';
$GLOBALS['TL_LANG']['tl_survey_question']['show']['0'] = 'Details';
$GLOBALS['TL_LANG']['tl_survey_question']['show']['1'] = 'Details der Frage ID %s anzeigen';
$GLOBALS['TL_LANG']['tl_survey_question']['edit']['0'] = 'Frage bearbeiten';
$GLOBALS['TL_LANG']['tl_survey_question']['edit']['1'] = 'Frage ID %s bearbeiten';
$GLOBALS['TL_LANG']['tl_survey_question']['copy']['0'] = 'Frage duplizieren';
$GLOBALS['TL_LANG']['tl_survey_question']['copy']['1'] = 'Frage ID %s duplizieren';
$GLOBALS['TL_LANG']['tl_survey_question']['cut']['0'] = 'Frage verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['cut']['1'] = 'Frage ID %s verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['up']['0'] = 'Nach oben verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['up']['1'] = 'Frage ID %s nach oben verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['down']['0'] = 'Nach unten verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['down']['1'] = 'Frage ID %s nach unten verschieben';
$GLOBALS['TL_LANG']['tl_survey_question']['delete']['0'] = 'Frage löschen';
$GLOBALS['TL_LANG']['tl_survey_question']['delete']['1'] = 'Frage ID %s löschen';
$GLOBALS['TL_LANG']['tl_survey_question']['details']['0'] = 'Detaillierte Statistik';
$GLOBALS['TL_LANG']['tl_survey_question']['details']['1'] = 'Detaillierte Statistik der Frage ID %s anzeigen';
$GLOBALS['TL_LANG']['tl_survey_question']['openended'] = 'Offene Frage';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_singleline'] = 'Einzeilig';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_multiline'] = 'Mehrzeilig';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_integer'] = 'Ganzzahl';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_float'] = 'Kommazahl';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_date'] = 'Datum';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_time'] = 'Uhrzeit';
$GLOBALS['TL_LANG']['tl_survey_question']['multiplechoice'] = 'Multiple Choice Frage';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_singleresponse'] = 'Einfachauswahl';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_multipleresponse'] = 'Mehrfachauswahl';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_dichotomous'] = 'Dichotom (Ja/Nein)';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix'] = 'Matrixfrage';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_singleresponse'] = 'Eine Antwort pro Zeile (Einfachauswahl)';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_multipleresponse'] = 'Mehrere Antworten pro Zeile (Mehrfachauswahl)';
$GLOBALS['TL_LANG']['tl_survey_question']['constantsum'] = 'Feste Summe';
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['0'] = 'Summe';
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['1'] = 'Geben Sie einen Wert für die Summe an.';
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['exact'] = 'Die zu den Antworten eingetragenen Werte müssen in der Summe exakt dem unten angegebenen Wert entsprechen.';
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['max'] = 'Die zu den Antworten eingetragenen Werte dürfen in der Summe den unten angegebenen Wert nicht überschreiten.';
$GLOBALS['TL_LANG']['tl_survey_question']['sumoption']['0'] = 'Berechnungsoption';
$GLOBALS['TL_LANG']['tl_survey_question']['sumoption']['1'] = 'Die zu den Antworten eingetragenen Werte müssen in der unten angegebenen Summe der ausgewählten Option entsprechen.';
$GLOBALS['TL_LANG']['tl_survey_question']['inputfirst']['0'] = 'Eingabefelder vor den Antworten anzeigen';
$GLOBALS['TL_LANG']['tl_survey_question']['inputfirst']['1'] = 'Wenn Sie diese Option wählen, werden die Eingabefelder vor dem Antworttext ausgegeben, anderenfall werden die Eingabefelder hinter dem Antworttext ausgegeben.';
$GLOBALS['TL_LANG']['tl_survey_question']['answered'] = 'Beantwortet';
$GLOBALS['TL_LANG']['tl_survey_question']['skipped'] = 'Übersprungen';
$GLOBALS['TL_LANG']['tl_survey_question']['most_selected_value'] = 'Häufigste Auswahl';
$GLOBALS['TL_LANG']['tl_survey_question']['nr_of_selections'] = 'Anzahl der Auswahlen';
$GLOBALS['TL_LANG']['tl_survey_question']['median'] = 'Median';
$GLOBALS['TL_LANG']['tl_survey_question']['arithmeticmean'] = 'Arithmetisches Mittel';
$GLOBALS['TL_LANG']['tl_survey_question']['yes'] = 'Ja';
$GLOBALS['TL_LANG']['tl_survey_question']['no'] = 'Nein';
$GLOBALS['TL_LANG']['tl_survey_question']['title_legend'] = 'Titel und Fragentyp';
$GLOBALS['TL_LANG']['tl_survey_question']['question_legend'] = 'Fragentext';
$GLOBALS['TL_LANG']['tl_survey_question']['obligatory_legend'] = 'Pflichteingabe';
$GLOBALS['TL_LANG']['tl_survey_question']['specific_legend'] = 'Fragenspezifische Einstellungen';
$GLOBALS['TL_LANG']['tl_survey_question']['rows_legend'] = 'Matrixzeilen';
$GLOBALS['TL_LANG']['tl_survey_question']['columns_legend'] = 'Matrixspalten';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolar_legend'] = 'Bipolare Attribute';
$GLOBALS['TL_LANG']['tl_survey_question']['sum_legend'] = 'Summenberechnung';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_new'] = 'Neue Antwort';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_copy'] = 'Antwort duplizieren';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_delete'] = 'Antwort löschen';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_new'] = 'Neue Zeile';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_copy'] = 'Zeile duplizieren';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_delete'] = 'Zeile löschen';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_new'] = 'Neue Spalte';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_copy'] = 'Spalte duplizieren';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_delete'] = 'Spalte löschen';
$GLOBALS['TL_LANG']['tl_survey_question']['cssClass'] = ['CSS-Klasse', 'Hier können Sie eine oder mehrere CSS-Klassen eingeben.'];
$GLOBALS['TL_LANG']['tl_survey_question']['expert_legend'] = 'Experten-Einstellungen';
