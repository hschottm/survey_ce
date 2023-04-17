-- --------------------------------------------------------
-- Host:                         localhost
-- Server Version:               10.5.10-MariaDB-log - mariadb.org binary distribution
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Exportiere Struktur von Tabelle tl_survey
DROP TABLE IF EXISTS `tl_survey`;
CREATE TABLE IF NOT EXISTS `tl_survey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resultCategories` blob DEFAULT NULL,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `language` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author` smallint(5) unsigned NOT NULL DEFAULT 0,
  `online_start` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `online_end` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `usecookie` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `limit_groups` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `show_title` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `show_cancel` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `allowed_groups` blob DEFAULT NULL,
  `introduction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `finalsubmission` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `allowback` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `immediate_start` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `jumpto` int(10) unsigned NOT NULL DEFAULT 0,
  `useResultCategories` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sendConfirmationMail` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sendConfirmationMailAlternate` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAlternateCondition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailRecipientField` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailRecipient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAlternateRecipient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailSender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAlternateSender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailReplyto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAlternateReplyto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailSubject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAlternateSubject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailText` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmationMailAlternateText` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmationMailTemplate` binary(16) DEFAULT NULL,
  `confirmationMailAlternateTemplate` binary(16) DEFAULT NULL,
  `sendFormattedMail` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `formattedMailRecipient` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formattedMailSubject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `formattedMailText` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formattedMailTemplate` binary(16) DEFAULT NULL,
  `formattedMailSkipEmpty` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `addConfirmationMailAttachments` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `addConfirmationMailAlternateAttachments` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirmationMailAttachments` blob DEFAULT NULL,
  `confirmationMailAlternateAttachments` blob DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey: ~1 rows (ungefähr)
DELETE FROM `tl_survey`;
INSERT INTO `tl_survey` (`id`, `resultCategories`, `tstamp`, `title`, `language`, `author`, `online_start`, `online_end`, `description`, `access`, `usecookie`, `limit_groups`, `show_title`, `show_cancel`, `allowed_groups`, `introduction`, `finalsubmission`, `allowback`, `immediate_start`, `jumpto`, `useResultCategories`, `sendConfirmationMail`, `sendConfirmationMailAlternate`, `confirmationMailAlternateCondition`, `confirmationMailRecipientField`, `confirmationMailRecipient`, `confirmationMailAlternateRecipient`, `confirmationMailSender`, `confirmationMailAlternateSender`, `confirmationMailReplyto`, `confirmationMailAlternateReplyto`, `confirmationMailSubject`, `confirmationMailAlternateSubject`, `confirmationMailText`, `confirmationMailAlternateText`, `confirmationMailTemplate`, `confirmationMailAlternateTemplate`, `sendFormattedMail`, `formattedMailRecipient`, `formattedMailSubject`, `formattedMailText`, `formattedMailTemplate`, `formattedMailSkipEmpty`, `addConfirmationMailAttachments`, `addConfirmationMailAlternateAttachments`, `confirmationMailAttachments`, `confirmationMailAlternateAttachments`) VALUES
	(3, _binary 0x613a323a7b693a313b613a323a7b733a383a2263617465676f7279223b733a31383a22416e74776f72746b617465676f7269652031223b733a323a226964223b693a303b7d693a323b613a323a7b733a383a2263617465676f7279223b733a31383a22416e74776f72746b617465676f7269652032223b733a323a226964223b693a313b7d7d, 1681719816, 'Referenzumfrage zum Testen des Bundles contao-survey', 'de', 1, '', '', 'Umfrage zum Testen von Multiple-Choice Fragen', 'anon', '', '0', '1', '1', NULL, '', '<p>Vielen Dank, dass Sie an der Umfrage teilgenommen haben.</p>', '1', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, '', NULL, '', NULL, NULL, '', '', '', NULL, NULL);

-- Exportiere Struktur von Tabelle tl_survey_condition
DROP TABLE IF EXISTS `tl_survey_condition`;
CREATE TABLE IF NOT EXISTS `tl_survey_condition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `grp` int(10) unsigned NOT NULL DEFAULT 0,
  `qid` int(10) unsigned NOT NULL DEFAULT 0,
  `pageid` int(10) unsigned NOT NULL DEFAULT 0,
  `relation` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '=',
  `condition` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_condition: ~0 rows (ungefähr)
DELETE FROM `tl_survey_condition`;

-- Exportiere Struktur von Tabelle tl_survey_navigation
DROP TABLE IF EXISTS `tl_survey_navigation`;
CREATE TABLE IF NOT EXISTS `tl_survey_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `pin` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT 0,
  `frompage` int(10) unsigned NOT NULL DEFAULT 0,
  `topage` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_navigation: ~0 rows (ungefähr)
DELETE FROM `tl_survey_navigation`;

-- Exportiere Struktur von Tabelle tl_survey_page
DROP TABLE IF EXISTS `tl_survey_page`;
CREATE TABLE IF NOT EXISTS `tl_survey_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `introduction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditions` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `page_template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'survey_questionblock',
  `pagetype` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `useCustomNextButtonTitle` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `customNextButtonTitle` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hideBackButton` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_page: ~5 rows (ungefähr)
DELETE FROM `tl_survey_page`;
INSERT INTO `tl_survey_page` (`id`, `type`, `tstamp`, `pid`, `sorting`, `title`, `description`, `language`, `introduction`, `conditions`, `page_template`, `pagetype`, `useCustomNextButtonTitle`, `customNextButtonTitle`, `hideBackButton`) VALUES
	(5, 'default', 1681716850, 3, 128, 'Frageseite 1 - Multiple-Choice-Fragen', 'Auf dieser Seite können die drei Typen der Multiple-Choice-Fragen getestet werden', '', '<p>Im Contao-Survey gibt es drei Typen von Miltiple-Choice-Fragen.</p>\n<ol>\n<li><strong>Multiple-Choice-Frage</strong> mit <strong>Einfachauswahl</strong>. Einfachauswahl meint hier, dass man unter allen angebotenen Antworten nur eine einzige wählen kann. Wird die Option <em><strong>Andere Antwort erlauben</strong></em> aktiviert, so kann zusätzlich zu den vorgegebenen Antworten noch eine &#39;freie&#39;, also vom Proband festgelegte, Antwort gegeben werden.<br><br></li>\n<li><strong>Multiple-Choice-Frage</strong> mit <strong>Mehrfachauswahl</strong>. Mehrfachauswahl meint hier, dass man unter allen angebotenen Antworten <strong>mehrere</strong> wählen kann. Wird die Option <em><strong>Andere Antwort erlauben</strong></em> aktiviert, so kann zusätzlich zu den vorgegebenen Antworten noch eine &#39;freie&#39;, also vom Proband festgelegte, Antwort gegeben werden.<br><br></li>\n<li><strong>Multiple-Choice-Frage</strong> mit <strong>binärer/dichotomer Auswahl</strong>. Dichotom meint hier, dass man nur zwischen zwei Antworten wählen kann. In der Regel[nbsp] sind das hier Ja und Nein. Die Option <em><strong>Andere Antwort erlauben</strong></em> existiert für diesen Fragetyp nicht.</li>\n</ol>\n<p>[nbsp]</p>', '', 'survey_questionblock', 'standard', '', '', ''),
	(6, 'result', 1680082351, 3, 256, 'Ergebnisseite', 'Alle Ergebnisse der Umfrage 1', '', '', '', '', 'standard', '', '', ''),
	(7, 'default', 1681723042, 3, 192, 'Frageseite 2 - Offene Fragen', 'Auf dieser Seite können die sechs Typen der Offenen Fragen getestet werden.', '', '<p>Im Contao-Survey gibt es sechs Typen von <strong>Offenen Fragen</strong>.</p>\n<ol>\n<li><strong>Einzeilig</strong>. </li>\n<li><strong>Mehrzeilig</strong>. </li>\n<li><strong>Ganzzahl. </strong></li>\n<li><strong>Kommazahl.<br></strong></li>\n<li><strong>Datum.<br></strong></li>\n<li><strong>Uhrzeit.<br></strong></li>\n</ol>\n<p>[nbsp]</p>', '', 'survey_questionblock', 'standard', '', '', ''),
	(8, 'default', 1681722683, 3, 224, 'Frageseite 3 - Matrixfragen', 'Auf dieser Seite können die zwei Typen der Matrixfragen getestet werden.', '', '<p>Im Contao-Survey gibt es zwei Typen von <strong>Matrixfragen</strong>.</p>\n<ol>\n<li><strong>Matrixfrage mit Einfachauswahl</strong>. </li>\n<li><strong>Matrixfrage mit Mehfachauswahl</strong>.</li>\n</ol>\n<p>[nbsp]</p>', '', 'survey_questionblock', 'standard', '', '', ''),
	(9, 'default', 1681726099, 3, 240, 'Frageseite 4 - Feste Summe', 'Auf dieser Seite kann der Fragetyp Feste Summe getestet werden.', '', '<p>Im Contao-Survey gibt es keinen Subtyp für die Frage <strong>Feste Summe</strong>!</p>', '', 'survey_questionblock', 'standard', '', '', '');

-- Exportiere Struktur von Tabelle tl_survey_participant
DROP TABLE IF EXISTS `tl_survey_participant`;
CREATE TABLE IF NOT EXISTS `tl_survey_participant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `uid` int(10) unsigned NOT NULL DEFAULT 0,
  `pin` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lastpage` int(10) unsigned NOT NULL DEFAULT 1,
  `finished` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `category` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_participant: ~0 rows (ungefähr)
DELETE FROM `tl_survey_participant`;

-- Exportiere Struktur von Tabelle tl_survey_pin_tan
DROP TABLE IF EXISTS `tl_survey_pin_tan`;
CREATE TABLE IF NOT EXISTS `tl_survey_pin_tan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `pin` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tan` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `used` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `pin` (`pin`),
  KEY `tan` (`tan`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_pin_tan: ~0 rows (ungefähr)
DELETE FROM `tl_survey_pin_tan`;

-- Exportiere Struktur von Tabelle tl_survey_question
DROP TABLE IF EXISTS `tl_survey_question`;
CREATE TABLE IF NOT EXISTS `tl_survey_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `alias` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `questiontype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` smallint(5) unsigned NOT NULL DEFAULT 0,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `introduction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `obligatory` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `complete` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `original` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `help` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hidetitle` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lower_bound` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `upper_bound` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lower_bound_date` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `upper_bound_date` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lower_bound_time` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `upper_bound_time` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_subtype` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_textbefore` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_textafter` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_rows` smallint(5) unsigned NOT NULL DEFAULT 5,
  `openended_cols` smallint(5) unsigned NOT NULL DEFAULT 40,
  `openended_width` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_maxlen` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `openended_textinside` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `multiplechoice_subtype` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `matrix_subtype` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mc_style` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `choices` blob DEFAULT NULL,
  `matrixrows` blob DEFAULT NULL,
  `matrixcolumns` blob DEFAULT NULL,
  `addneutralcolumn` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `neutralcolumn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `addother` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `addbipolar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `adjective1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `adjective2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bipolarposition` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `othertitle` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `inputfirst` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sumoption` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sumchoices` blob DEFAULT NULL,
  `sum` double NOT NULL DEFAULT 0,
  `cssClass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_question: ~12 rows (ungefähr)
DELETE FROM `tl_survey_question`;
INSERT INTO `tl_survey_question` (`id`, `tstamp`, `pid`, `sorting`, `alias`, `questiontype`, `title`, `description`, `author`, `language`, `question`, `introduction`, `obligatory`, `complete`, `original`, `help`, `hidetitle`, `lower_bound`, `upper_bound`, `lower_bound_date`, `upper_bound_date`, `lower_bound_time`, `upper_bound_time`, `openended_subtype`, `openended_textbefore`, `openended_textafter`, `openended_rows`, `openended_cols`, `openended_width`, `openended_maxlen`, `openended_textinside`, `multiplechoice_subtype`, `matrix_subtype`, `mc_style`, `choices`, `matrixrows`, `matrixcolumns`, `addneutralcolumn`, `neutralcolumn`, `addother`, `addbipolar`, `adjective1`, `adjective2`, `bipolarposition`, `othertitle`, `inputfirst`, `sumoption`, `sumchoices`, `sum`, `cssClass`) VALUES
	(9, 1681715744, 5, 128, 'multiple-choice-frage-mit-einfachauswahl', 'multiplechoice', 'Multiple-Choice-Frage mit Einfachauswahl', 'Multiple-Choice-Frage mit Einfachauswahl', 1, 'de', '<p>Fragetext zur Multiple-Choice-Frage mit Einfachauswahl</p>\n<ul>\n<li><strong>nicht </strong>verpflichtend</li>\n<li>mit drei Antwortmöglichkeiten</li>\n<li>andere Antwort ist zugelassen</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', '', '', 5, 40, '', '', '', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', _binary 0x613a333a7b693a313b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742031223b7d693a323b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742032223b7d693a333b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742033223b7d7d, NULL, NULL, '', '', '1', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(10, 1681715706, 5, 256, 'multiple-choice-frage-mit-mehrfachauswahl', 'multiplechoice', 'Multiple-Choice-Frage mit Mehrfachauswahl', 'Multiple-Choice-Frage mit Mehrfachauswahl', 1, 'de', '<p>Fragetext Multiple-Choice-Frage mit Mehrfachauswahl</p>\n<ul>\n<li>nicht verpflichtend</li>\n<li>mit drei Antwortmöglichkeiten</li>\n<li>andere Antwort ist zugelassen</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', '', '', 5, 40, '', '', '', 'mc_multipleresponse', 'matrix_singleresponse', 'vertical', _binary 0x613a333a7b693a313b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742031223b7d693a323b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742032223b7d693a333b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742033223b7d7d, NULL, NULL, '', '', '1', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(11, 1681716699, 5, 384, 'multiple-choice-frage-mit-zwei-antworten-ja-nein-dichotom', 'multiplechoice', 'Multiple-Choice-Frage mit zwei Antworten Ja/Nein - Dichotom', 'Multiple-Choice-Frage mit zwei Antworten Ja/Nein - DichotomMulti-Choice - Dichotom', 1, 'de', '<p>Fragetext Multiple-Choice-Frage mit zwei Antworten Ja/Nein - Dichotom</p>\n<ul>\n<li>nicht verpflichtend</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', '', '', 5, 40, '', '', '', 'mc_dichotomous', 'matrix_singleresponse', 'vertical', _binary 0x613a333a7b693a313b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742031223b7d693a323b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742032223b7d693a333b613a313a7b733a363a2263686f696365223b733a393a22416e74776f72742033223b7d7d, NULL, NULL, '', '', '1', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(12, 1681720144, 7, 128, 'offene-frage-einzeilig', 'openended', 'Offene Frage - Einzeilig', 'Offene Frage - Einzeilig', 1, 'de', '<p>Fragetext zur offenen Frage - Einzeilig</p>\n<ul>\n<li>nicht <strong>verpflichtend</strong></li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', 'Vorbelegung', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(13, 1681720194, 7, 256, 'offene-frage-mehrzeilig', 'openended', 'Offene Frage - Mehrzeilig', 'Offene Frage - Mehrzeilig', 1, 'de', '<p>Fragetext zur offenen Frage - Mehrzeilig</p>\n<ul>\n<li>nicht <strong>verpflichtend</strong></li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_multiline', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', 'Vorbelegung', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(14, 1681723117, 7, 384, 'offene-frage-ganzzahl', 'openended', 'Offene Frage - Ganzzahl', 'Offene Frage - Ganzzahl', 1, 'de', '<p>Fragetext zur offenen Frage - Ganzzahl</p>\n<ul>\n<li>nicht <strong>verpflichtend<br></strong></li>\n<li>willkürlicher Wertebereich von 0 bis 100</li>\n</ul>', '', '', '1', '1', '', '', '0', '100', '', '', '', '', 'oe_integer', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', '42', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(15, 1681723130, 7, 512, 'offene-frage-kommazahl', 'openended', 'Offene Frage - Kommazahl', 'Offene Frage - Kommazahl', 1, 'de', '<p>Fragetext zur offenen Frage - Kommazahl</p>\n<ul>\n<li>nicht <strong>verpflichtend<br></strong></li>\n<li>willkürlicher Wertebereich von 0.9 bis 92.5</li>\n</ul>', '', '', '1', '1', '', '', '0.9', '92.5', '', '', '', '', 'oe_float', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', '4,2', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(16, 1681723147, 7, 640, 'offene-frage-datum', 'openended', 'Offene Frage - Datum', 'Offene Frage - Datum', 1, 'de', '<p>Fragetext zur offenen Frage - Datum</p>\n<ul>\n<li>nicht <strong>verpflichtend<br></strong></li>\n<li>willkürlicher Wertebereich vom 01.01.2023 bis 31.12.2023</li>\n</ul>', '', '', '1', '1', '', '', '0.9', '92.5', '1672531200', '1703980800', '', '', 'oe_date', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', '01.05.2023', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(17, 1681723158, 7, 768, 'offene-frage-uhrzeit', 'openended', 'Offene Frage - Uhrzeit', 'Offene Frage - Uhrzeit', 1, 'de', '<p>Fragetext zur offenen Frage - Uhrzeit</p>\n<ul>\n<li>nicht <strong>verpflichtend<br></strong></li>\n<li>willkürlicher Wertebereich vom 10:00 bis 12:00 Uhr</li>\n</ul>', '', '', '1', '1', '', '', '0.9', '92.5', '1672531200', '1703980800', '36000', '43200', 'oe_time', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', '11:00', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, NULL, NULL, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(18, 1681721642, 8, 128, 'matrixfrage-einfachauswahl', 'matrix', 'Matrixfrage - Einfachauswahl', 'Matrixfrage - Einfachauswahl', 1, 'de', '<p>Fragetext zur Matrixfrage - Einfachauswahl</p>\n<ul>\n<li>nicht <strong>verpflichtend</strong></li>\n<li>willkürlich gewählte Größe von <strong>4 x 4 &#61; Zeilen x Spalten</strong></li>\n<li>neutrale Spalte ist <strong>nicht </strong>erlaubt</li>\n<li>entgegengesetzte Pole werden <strong>nicht </strong>angezeigt</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', 'Vorbelegung', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, _binary 0x613a343a7b693a303b733a373a225a65696c652031223b693a313b733a373a225a65696c652032223b693a323b733a373a225a65696c652033223b693a333b733a373a225a65696c652034223b7d, _binary 0x613a343a7b693a303b733a383a225370616c74652031223b693a313b733a383a225370616c74652032223b693a323b733a383a225370616c74652033223b693a333b733a383a225370616c74652034223b7d, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(24, 1681722976, 8, 256, 'matrixfrage-mehrfachauswahl', 'matrix', 'Matrixfrage - Mehrfachauswahl', 'Matrixfrage - Mehrfachauswahl', 1, 'de', '<p>Fragetext zur Matrixfrage - Mehrfachauswahl</p>\n<ul>\n<li>nicht <strong>verpflichtend</strong></li>\n<li>willkürlich gewählte Größe von <strong>4 x 4 &#61; Zeilen x Spalten</strong></li>\n<li>neutrale Spalte ist <strong>nicht </strong>erlaubt</li>\n<li>entgegengesetzte Pole werden <strong>nicht </strong>angezeigt</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', 'Vorbelegung', 'mc_singleresponse', 'matrix_multipleresponse', 'vertical', NULL, _binary 0x613a343a7b693a303b733a373a225a65696c652031223b693a313b733a373a225a65696c652032223b693a323b733a373a225a65696c652033223b693a333b733a373a225a65696c652034223b7d, _binary 0x613a343a7b693a303b733a383a225370616c74652031223b693a313b733a383a225370616c74652032223b693a323b733a383a225370616c74652033223b693a333b733a383a225370616c74652034223b7d, '', '', '', '', '', '', 'top', '', '', 'exact', NULL, 100, ''),
	(25, 1681724831, 9, 128, 'feste-summe', 'constantsum', 'Feste Summe', 'Feste Summe', 1, 'de', '<p>Fragetext zum Fragetyp Festen Summe</p>\n<ul>\n<li>nicht <strong>verpflichtend</strong></li>\n<li>willkürlich gewählte <strong>vier </strong>Antworten</li>\n<li>Summe 100</li>\n</ul>', '', '', '1', '1', '', '', '', '', '', '', '', '', 'oe_singleline', 'Beschriftung vor Textfeld', 'Beschirftung nach Textfeld', 5, 40, '20', '20', 'Vorbelegung', 'mc_singleresponse', 'matrix_singleresponse', 'vertical', NULL, _binary 0x613a343a7b693a303b733a373a225a65696c652031223b693a313b733a373a225a65696c652032223b693a323b733a373a225a65696c652033223b693a333b733a373a225a65696c652034223b7d, _binary 0x613a343a7b693a303b733a383a225370616c74652031223b693a313b733a383a225370616c74652032223b693a323b733a383a225370616c74652033223b693a333b733a383a225370616c74652034223b7d, '', '', '', '', '', '', 'top', '', '', 'exact', _binary 0x613a343a7b693a303b733a31303a22416e74776f7274203235223b693a313b733a31303a22416e74776f7274203235223b693a323b733a31303a22416e74776f7274203235223b693a333b733a31303a22416e74776f7274203235223b7d, 100, '');

-- Exportiere Struktur von Tabelle tl_survey_result
DROP TABLE IF EXISTS `tl_survey_result`;
CREATE TABLE IF NOT EXISTS `tl_survey_result` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `pin` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT 0,
  `qid` int(10) unsigned NOT NULL DEFAULT 0,
  `result` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `qid` (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=892 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_result: ~0 rows (ungefähr)
DELETE FROM `tl_survey_result`;

-- Exportiere Struktur von Tabelle tl_survey_scale
DROP TABLE IF EXISTS `tl_survey_scale`;
CREATE TABLE IF NOT EXISTS `tl_survey_scale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scale` blob DEFAULT NULL,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_scale: ~0 rows (ungefähr)
DELETE FROM `tl_survey_scale`;

-- Exportiere Struktur von Tabelle tl_survey_scale_folder
DROP TABLE IF EXISTS `tl_survey_scale_folder`;
CREATE TABLE IF NOT EXISTS `tl_survey_scale_folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Exportiere Daten aus Tabelle tl_survey_scale_folder: ~0 rows (ungefähr)
DELETE FROM `tl_survey_scale_folder`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
