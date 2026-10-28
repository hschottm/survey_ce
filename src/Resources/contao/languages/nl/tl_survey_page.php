<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Titel';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Voer de paginatitel in.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Beschrijving';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Voer de paginabeschrijving in.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Laatste wijziging';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Datum en tijd van de laatste wijziging.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Introductie';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Voer een pagina-introductie in. De introductie wordt aan het begin van de pagina weergegeven.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Paginasjabloon';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Hier kunt u het paginasjabloon selecteren.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nieuwe pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Maak een nieuwe pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Details tonen';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Details van pagina-ID %s weergeven';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Pagina verplaatsen';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Pagina-ID %s verplaatsen';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Pagina bewerken';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Pagina-ID %s bewerken';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Dubbele pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'Dubbele pagina-ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Pagina verwijderen';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Pagina-ID %s verwijderen';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titel en beschrijving';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introductie';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Sjablooninstellingen';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigatie na deze pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Navigatieregels';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Regels worden geëvalueerd als u na deze pagina verdergaat.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Regels worden van boven naar beneden gecontroleerd. De eerste matchingregel bepaalt de volgende pagina. Alle voorwaarden binnen een regel moeten overeenkomen. Ontbrekende antwoorden voldoen nooit aan een voorwaarde.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Als geen enkele regel overeenkomt, volgt de volgende pagina in volgorde. Een “Altijd”-regel aan het einde kan een andere standaardbestemming definiëren.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Vraagkeuzes filteren';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Zoek op vraagtitel of ID';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Regel';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'Wanneer';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Alle volgende voorwaarden komen overeen (EN)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Altijd';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Vraag';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Vergelijking';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Antwoord';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Ga dan verder';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Selecteer een vraag';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Selecteer een antwoord';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Selecteer een pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Voeg nog een regel toe';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Regel verwijderen';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Voeg nog een AND-voorwaarde toe';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Conditie verwijderen';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Omhoog verplaatsen';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Omlaag verplaatsen';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'bevat antwoord';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'bevat geen antwoord';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'gelijk aan';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'is niet gelijk';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'is groter dan';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'is minder dan';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'groter is dan of gelijk is aan';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'kleiner is dan of gelijk is aan';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Controleer alle navigatieregels: vraag, vergelijking, antwoord en bestemming moeten geldig zijn. De bestemming moet een andere pagina van dezelfde enquête zijn. Er zijn maximaal 100 regels met elk 100 voorwaarden toegestaan.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Deze bestaande regel bevat een niet-beschikbare of momenteel niet-ondersteunde voorwaarde. Het wordt onveranderd bewaard. U kunt de regel verplaatsen of verwijderen en vervangen door een nieuwe regel.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Bestaande waarde:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Deze regel komt altijd overeen. Regels daaronder worden niet gehaald.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'JavaScript moet zijn ingeschakeld om regels te kunnen bewerken. Bij het opslaan blijven bestaande regels behouden.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Vragen bewerken';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Vragen bewerken (ID %s)';
