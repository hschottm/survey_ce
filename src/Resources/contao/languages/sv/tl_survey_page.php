<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Titel';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Vänligen ange sidtiteln.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Beskrivning';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Vänligen ange sidbeskrivningen.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Senaste förändringen';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Datum och tid för den senaste ändringen.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Introduktion';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Ange en introduktion till sidan. Inledningen kommer att visas i början av sidan.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Sidmall';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Här kan du välja sidmall.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Ny sida';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Skapa en ny sida';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Visa detaljer';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Visa detaljer om sid-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Flytta sidan';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Flytta sid-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Redigera sida';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Redigera sid-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Dubblettsida';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'Duplicera sid-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Ta bort sida';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Ta bort sid-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Sida';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titel och beskrivning';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introduktion';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Mallinställningar';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigering efter denna sida';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Navigeringsregler';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Regler utvärderas när du fortsätter efter denna sida.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Reglerna kontrolleras uppifrån och ned. Den första matchningsregeln avgör nästa sida. Alla villkor inom en regel måste matcha. Saknade svar uppfyller aldrig ett villkor.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Om ingen regel matchar följer nästa sida i följd. En "Alltid"-regel i slutet kan definiera en annan standarddestination.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtrera frågeval';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Sök efter frågas titel eller ID';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Regel';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'När';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Alla följande villkor matchar (OCH)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Alltid';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Fråga';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Jämförelse';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Svara';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Fortsätt sedan till';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Välj en fråga';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Välj ett svar';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Välj en sida';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Lägg till en annan regel';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Ta bort regeln';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Lägg till ytterligare ett OCH-villkor';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Ta bort skick';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Flytta upp';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Flytta ned';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'innehåller svar';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'innehåller inget svar';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'lika';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'är inte lika';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'är större än';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'är mindre än';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'är större än eller lika med';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'är mindre än eller lika med';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Kontrollera alla navigeringsregler: fråga, jämförelse, svar och destination måste vara giltiga. Destinationen måste vara en annan sida i samma undersökning. Högst 100 regler med 100 villkor vardera är tillåtna.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Den här befintliga regeln innehåller ett villkor som inte är tillgängligt eller som för närvarande inte stöds. Den bevaras oförändrad. Du kan flytta eller ta bort regeln och ersätta den med en ny regel.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Befintligt värde:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Denna regel stämmer alltid. Regler nedan kommer inte att nås.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'JavaScript måste vara aktiverat för att redigera regler. Befintliga regler bevaras när du sparar.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Redigera frågor';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Redigera frågor (ID %s)';
