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

$GLOBALS['TL_LANG']['tl_survey'] = [
    // dca commmon operations
    'new' => ['Neue Umfrage', 'Eine neue Umfrage anlegen'],
    'show' => ['Umfragedetails', 'Details der Umfrage ID %s anzeigen'],
    'edit' => ['Umfrage bearbeiten', 'Umfrage ID %s bearbeiten'],
    'edit_' => ['Umfrage kann nicht bearbeitet werden', 'Die Umfrage ID %s kann nicht bearbeitet werden, da bereits Teilnehmerdatensätze existieren.'],
    'copy' => ['Umfrage duplizieren', 'Umfrage ID %s duplizieren'],
    'delete' => ['Umfrage löschen', 'Umfrage ID %s löschen'],
    // special operations
    'pintan' => ['TAN-Erzeugung', 'TAN-Erzeugung für die Umfrage ID %s öffnen'],
    'participants' => ['Umfrageteilnehmer', 'Teilnehmer der Umfrage ID %s bearbeiten'],
    'statistics' => ['Statistik', 'Statistik der Umfrage ID %s anzeigen'],
];

// title
$GLOBALS['TL_LANG']['tl_survey']['title_legend'] = 'Titel und Beschreibung';
$GLOBALS['TL_LANG']['tl_survey']['title'] = ['Titel', 'Bitte geben Sie den Titel der Umfrage ein.'];
$GLOBALS['TL_LANG']['tl_survey']['author'] = ['Autor', 'Bitte geben Sie den Namen des Autors ein.'];
$GLOBALS['TL_LANG']['tl_survey']['description'] = ['Beschreibung', 'Bitte geben Sie eine Beschreibung für die Umfrage ein.'];
$GLOBALS['TL_LANG']['tl_survey']['tstamp'] = ['Zuletzt geändert', 'Datum und Uhrzeit der letzten Änderung der Umfrage.'];
$GLOBALS['TL_LANG']['tl_survey']['language'] = ['Sprache', 'Bitte wählen Sie die Sprache der Umfrage.'];
$GLOBALS['TL_LANG']['tl_survey']['duration'] = ['Geschätzte Dauer der Umfrage in Minuten (max. 255)', 'Hier können Sie die geschätzte Dauer (in Minuten) zur Bearbeitung der Umfrage angeben.'];
// activation
$GLOBALS['TL_LANG']['tl_survey']['activation_legend'] = 'Aktivierung';
$GLOBALS['TL_LANG']['tl_survey']['online_start'] = ['Aktiviert ab', 'Wenn Sie hier ein Datum erfassen, wird die Umfrage erst ab diesem Tag aktiviert.'];
$GLOBALS['TL_LANG']['tl_survey']['online_end'] = ['Aktiviert bis', 'Wenn Sie hier ein Datum erfassen, wird die Umfrage nur bis zu diesem Tag aktiviert.'];
// access
$GLOBALS['TL_LANG']['tl_survey']['access_legend'] = 'Zugriff';
$GLOBALS['TL_LANG']['tl_survey']['access'] = ['Umfrageverfahren', 'Bitte wählen Sie das von Ihnen gewünschte Umfrageverfahren aus.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['explanation'] = 'Bitte wählen Sie das von Ihnen gewünschte Umfrageverfahren aus.';
$GLOBALS['TL_LANG']['tl_survey']['access_template'] = "<h1>Sie beabsichtigen TANs für eine &raquo;%s&laquo; zu generieren</h1><p>%s</p><p style='color:#F47C00;'>%s</p>";
$GLOBALS['TL_LANG']['tl_survey']['access']['anon'] = ['Anonymisierte Umfrage', 'Umfrageteilnehmer benötigen keinen Zugangscode für die Umfrage. Eine Zuordnung der Umfrageteilnehmer zu den Umfrageergebnissen ist nicht möglich.', ''];
$GLOBALS['TL_LANG']['tl_survey']['access']['anoncode'] = ['Anonymisierte oder Personalisierte Umfrage mit TAN', 'Umfrageteilnehmer können die Umfrage nur mit einem Zugangscode (Transaktiosnnummer, TAN) starten. Eine Umfrage kann nur genau ein Mal pro Teilnehmer durchgeführt werden. Eine Zuordnung der Umfrageteilnehmer zu den Umfrageergebnissen ist bei der &raquo;anonymisierten Form&laquo; nicht möglich. Wählen Sie die &raquo;personalisierte Form&laquo;, um die Teilnehmer der Umfrage zuzordnen.', 'Sie können hier weiterhin angeben, wie viele TANs Sie für die Umfrage generieren wollen. Auf diese Weise können Sie festlegen, wie viele Personen anonym an der Umfrage teilnehmen können. Sind alle TANs aufgebraucht, so ist auch die Umfrage abgeschlossen.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['nonanoncode'] = ['Personalisierte Umfrage', 'Umfrageteilnehmer können die Umfrage nur starten, wenn Sie angemeldete Mitglieder sind. Eine Umfrage kann nur genau ein Mal pro Teilnehmer durchgeführt werden. Die Umfrageergebnisse können in der Auswertung den einzelnen Umfrageteilnehmern zugeordnet werden.', 'Diese personalisierte Umfrage ist %s beschränkt!'];
$GLOBALS['TL_LANG']['tl_survey']['usecookie'] = ['Teilnehmer wiedererkennen', 'Bitte wählen Sie, ob Teilnehmer mit Hilfe eines Cookies wiedererkannt werden sollen.'];
$GLOBALS['TL_LANG']['tl_survey']['access']['group'] = ['auf keine Mitgliedergruppe', 'auf folgende Mitgliedergruppen: %s'];
$GLOBALS['TL_LANG']['tl_survey']['limit_groups'] = ['Limitieren auf Mitgliedergruppen', 'Limitieren Sie hier den Zugriff auf ausgewählte Mitgliedergruppen. Wenn Sie keine Gruppe auswählen, werden alle aktiven Mitglieder als Teilnehmer verwendet.'];
$GLOBALS['TL_LANG']['tl_survey']['allowed_groups'] = ['Mitgliedergruppen', 'Wählen Sie die Mitgliedergruppen aus, welche die Umfrage durchführen dürfen.'];

$GLOBALS['TL_LANG']['tl_survey']['useNotifications'] = ['Notification Center verwenden', 'Wenn Sie die Funktionen des Notification Centers verwenden möchten, so müssen Sie zuerst geeignete Benachrichtigungen definieren.'];
$GLOBALS['TL_LANG']['tl_survey']['useNotificationsNotInstalled'] = ["Notification Center verwenden (nicht installiert)", "<span style='color:red;'>Sie können diese Funktion nicht verwenden, da das Notification Center aktuell nicht installiert ist. Bitte wenden Sie sich an Ihren Administrator, um diese Funktion zu aktivieren.</span>"];

$GLOBALS['TL_LANG']['tl_survey']['invitationNotificationId'] = ['Diese Nachricht als Einladung verwenden.', 'Wählen Sie hier die Nachricht für die Einladung aus.'];
$GLOBALS['TL_LANG']['tl_survey']['reminderNotificationId'] = ['Diese Nachricht als Erinnerung verwenden.', 'Wählen Sie hier die Nachricht für die Erinnerung aus.'];
$GLOBALS['TL_LANG']['tl_survey']['surveyPage'] = ['Seite der Umfrage', 'Wenn Sie Empfänger*Innen einladen oder erinnern möchten, dann müssen Sie hier die Seite auswählen, auf die sich die Umfrage befindet. Die Einladung oder Erinnerung enthält einen Link zu dieser Seite, damit die Teilnehmer*Innen die Umfrage direkt aufrufen können.'];
// texts
$GLOBALS['TL_LANG']['tl_survey']['texts_legend'] = 'Einleitende und abschließende Bemerkung';
$GLOBALS['TL_LANG']['tl_survey']['introduction'] = ['Einleitender Text', 'Bitte geben Sie einen einleitenden Text für die Umfrage ein, der auf einer gesonderten Startseite erscheint.'];
$GLOBALS['TL_LANG']['tl_survey']['finalsubmission'] = ['Abschließender Text', 'Bitte geben Sie einen abschließenden Text ein, der nach dem Beenden der Umfrage erscheint.'];
// head
$GLOBALS['TL_LANG']['tl_survey']['head_legend'] = 'Einstellungen für die Kopfzeile';
$GLOBALS['TL_LANG']['tl_survey']['show_title'] = ['Umfragetitel anzeigen', 'Zeigt den Umfragetitel dauerhaft in einer Kopfzeile über der Umfrage an.'];
$GLOBALS['TL_LANG']['tl_survey']['show_cancel'] = ['Diese Umfrage beenden', 'Zeigt während der Umfrage dauerhaft ein <strong>Diese Umfrage beenden</strong> Kommando in einer Kopfzeile über der Umfrage an.'];
// send confirmation email
$GLOBALS['TL_LANG']['tl_survey']['sendconfirmationmail_legend'] = 'Bestätigung per E-Mail versenden';
$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMail'] = ['Bestätigung per E-Mail versenden', 'Wenn Sie diese Option wählen, wird eine Bestätigung per E-Mail an den Absender des Formulars versendet.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipientField'] = ['Formularfeld mit E-Mail-Adresse des Empfängers', 'Wählen Sie hier das Formularfeld, in dem der Absender seine E-Mail-Adresse angibt oder ein Formularfeld, das die Empfänger-Adresse als Wert enthält.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipient'] = ['Empfänger', 'Kommagetrennte Liste von E-Mail-Adressen, falls die E-Mail-Adresse nicht per Formularfeld definiert wird, oder die E-Mail an weitere Empfänger gesendet werden soll.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSender'] = ['Absender', 'Bitte geben Sie hier die Absender-E-Mail-Adresse ein.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailReplyto'] = ['Antwort an (Reply-To)', 'Kommagetrennte Liste von E-Mail-Adressen, falls Antworten auf die E-Mail nicht an den Absender gesendet werden sollen.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSubject'] = ['Betreff', 'Bitte geben Sie eine Betreffzeile für die Bestätigungs-E-Mail ein. Wenn Sie keine Betreffzeile erfassen, steigt die Wahrscheinlichkeit, dass die E-Mail als SPAM identifiziert wird.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailText'] = ['Text der Bestätigungs-E-Mail', 'Bitte geben Sie hier den Text der Bestätigungs-E-Mail ein. Neben den allgemeinen Insert-Tags werden Tags der Form form::FORMULARFELDNAME unterstützt.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailTemplate'] = ['HTML-Vorlage für die Bestätigungs-E-Mail', 'Wenn die Bestätigungs-E-Mail als HTML-E-Mail versendet werden soll, wählen Sie hier die HTML-Vorlage aus dem Dateisystem.'];
$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAttachments'] = ['Dateien an Bestätigungs-E-Mail anhängen', 'Der Bestätigungs-E-Mail können hier Dateien zum Versand angehängt werden.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAttachments'] = ['Dateianhänge', 'Bitte wählen Sie hier die anzuhängenden Dateien aus.'];
// send alternate email
$GLOBALS['TL_LANG']['tl_survey']['sendformattedmail_legend'] = 'Per E-Mail versenden';
$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMailAlternate'] = ['Alternative Bestätigung per E-Mail versenden', 'Wenn Sie diese Option wählen, wird eine alternative Bestätigung per E-Mail an den Absender des Formulars versendet.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateCondition'] = ['Sendebedingung', 'Wenn Sie möchten, dass die alternative E-Mail nur unter einer bestimmten Bedingung versendet wird, geben Sie bitte eine Bedingung basierend auf einer bestimmten Frage an, z.B. \'{{q::geschlecht}}\' == \'männlich\'.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateRecipient'] = ['Alternativer Empfänger', 'Kommagetrennte Liste von E-Mail-Adressen für die Empfänger der alternativen E-Mail.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSender'] = ['Alternativer Absender', 'Bitte geben Sie hier die Absender-E-Mail-Adresse der alternativen E-Mail ein.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateReplyto'] = ['Antwort an (Reply-To)', 'Kommagetrennte Liste von E-Mail-Adressen, falls Antworten auf die alternative E-Mail nicht an den Absender gesendet werden sollen.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSubject'] = ['Alternativer Betreff', 'Bitte geben Sie eine Betreffzeile für die alternative Bestätigungs-E-Mail ein. Wenn Sie keine Betreffzeile erfassen, steigt die Wahrscheinlichkeit, dass die E-Mail als SPAM identifiziert wird.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateText'] = ['Text der alternativen Bestätigungs-E-Mail', 'Bitte geben Sie hier den Text der alternativen Bestätigungs-E-Mail ein. Neben den allgemeinen Insert-Tags werden Tags der Form form::FORMULARFELDNAME unterstützt.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateTemplate'] = ['HTML-Vorlage für die alternative Bestätigungs-E-Mail', 'Wenn die alternative Bestätigungs-E-Mail als HTML-E-Mail versendet werden soll, wählen Sie hier die HTML-Vorlage aus dem Dateisystem.'];
$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAlternateAttachments'] = ['Dateien an alternative Bestätigungs-E-Mail anhängen', 'Der alternativen Bestätigungs-E-Mail können hier Dateien zum Versand angehängt werden.'];
$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateAttachments'] = ['Alternative Dateianhänge', 'Bitte wählen Sie hier die anzuhängenden Dateien aus.'];
$GLOBALS['TL_LANG']['tl_survey']['addFormattedMailAttachments'] = ['Dateien an E-Mail anhängen', 'Der E-Mail können hier Dateien zum Versand angehängt werden.'];
$GLOBALS['TL_LANG']['tl_survey']['formattedMailAttachments'] = ['Dateianhänge', 'Bitte wählen Sie hier die anzuhängenden Dateien aus.'];
$GLOBALS['TL_LANG']['tl_survey']['sendFormattedMail'] = ['Per E-Mail versenden (formatierter Text / HTML)', 'Der Inhalt der Nachricht kann frei angegeben werden, unter Verwendung von Insert-Tags. Die Nachricht kann auch als HTML-E-Mail versendet werden.'];
$GLOBALS['TL_LANG']['tl_survey']['formattedMailText'] = ['Text der E-Mail', 'Bitte geben Sie hier den Text der E-Mail ein. Neben den allgemeinen Insert-Tags werden Tags der Form form::FORMULARFELDNAME unterstützt.'];
$GLOBALS['TL_LANG']['tl_survey']['formattedMailTemplate'] = ['HTML-Vorlage für die E-Mail', 'Wenn die E-Mail als HTML-E-Mail versendet werden soll, wählen Sie hier die HTML-Vorlage aus dem Dateisystem.'];
// misc
$GLOBALS['TL_LANG']['tl_survey']['misc_legend'] = 'Allgemeine Einstellungen';
$GLOBALS['TL_LANG']['tl_survey']['allowback'] = ['Zurückgehen erlauben', 'Bitte wählen Sie, ob Teilnehmer auf eine vorherige Seite der Umfrage zurückgehen dürfen.'];
$GLOBALS['TL_LANG']['tl_survey']['immediate_start'] = ['Umfrage sofort starten', 'Bitte wählen Sie, ob das Formular der Umfrage sofort angezeigt werden soll.'];
$GLOBALS['TL_LANG']['tl_survey']['allow_autostart'] = ['Umfrage automatisch starten','Wenn Sie diese Funktion aktivieren, dann wird beim Aufruf der Umfrageseite durch einen Link mit TAN die Umfrage automatisch gestartet. Die TAN muss also nicht extra eingegeben und bestätigt werden. Für diese Funktion muss jQuery aktiviert sein!'];
$GLOBALS['TL_LANG']['tl_survey']['jumpto'] = ['Weiterleitung zu Seite', 'Mit dieser Einstellung legen Sie fest, auf welche Seite ein Benutzer nach dem Beenden der Umfrage weitergeleitet wird.'];
$GLOBALS['TL_LANG']['tl_survey']['useResultCategories'] = ['Antwort-Kategorien verwenden', 'Aktivieren Sie diese Option, um Kategorien für Antworten (nur bei Multiplie Choice) zu verwenden.'];
$GLOBALS['TL_LANG']['tl_survey']['resultCategories'] = ['Antwort-Kategorien', 'Hier können Sie Antwort-Kategorien anlegen.'];
$GLOBALS['TL_LANG']['tl_survey']['resultCategories_'] = [
    'id' => ['ID', 'Die interne ID der Kategorie. Kann nicht bearbeitet werden.'],
    'category' => ['Titel', 'Der Titel der Kategorie.'],
];

// unused?
$GLOBALS['TL_LANG']['tl_survey']['skipEmpty'] = ['Leere Felder auslassen', 'Leere Felder in der E-Mail nicht anzeigen.'];
