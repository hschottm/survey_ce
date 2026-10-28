<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Naslov';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Vnesite naslov strani.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Opis';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Vnesite opis strani.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Zadnja sprememba';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Datum in čas zadnje spremembe.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Uvod';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Vnesite predstavitev strani. Uvod bo prikazan na začetku strani.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Predloga strani';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Tukaj lahko izberete predlogo strani.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nova stran';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Ustvarite novo stran';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Pokaži podrobnosti';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Prikaži podrobnosti ID-ja strani %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Premakni stran';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Premakni ID strani %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Uredi stran';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Uredi ID strani %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Podvojena stran';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'ID podvojene strani %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Izbriši stran';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Izbriši ID strani %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Stran';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Naslov in opis';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Uvod';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Nastavitve predloge';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigacija po tej strani';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Navigacijska pravila';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Pravila se ovrednotijo, ko nadaljujete po tej strani.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Pravila se preverjajo od zgoraj navzdol. Prvo pravilo ujemanja določa naslednjo stran. Vsi pogoji v pravilu se morajo ujemati. Manjkajoči odgovori nikoli ne izpolnjujejo pogoja.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Če se nobeno pravilo ne ujema, sledi naslednja stran v zaporedju. Pravilo »Vedno« na koncu lahko določi drugačen privzeti cilj.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtrirajte izbire vprašanj';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Iščite po naslovu vprašanja ali ID-ju';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Pravilo';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'kdaj';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Vsi naslednji pogoji se ujemajo (IN)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Vedno';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'vprašanje';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Primerjava';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Odgovori';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Nato nadaljujte';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Izberite vprašanje';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Prosimo izberite odgovor';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Izberite stran';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Dodajte še eno pravilo';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Odstrani pravilo';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Dodajte še en pogoj IN';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Odstrani stanje';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Premakni navzgor';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Premakni navzdol';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'vsebuje odgovor';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'ne vsebuje odgovora';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'enako';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'ni enako';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'je večji od';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'je manj kot';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'je večje ali enako';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'je manjše ali enako';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Preverite vsa pravila navigacije: vprašanje, primerjava, odgovor in cilj morajo biti veljavni. Cilj mora biti druga stran iste ankete. Dovoljenih je največ 100 pravil s 100 pogoji.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'To obstoječe pravilo vsebuje nerazpoložljiv ali trenutno nepodprt pogoj. Ohranjen je nespremenjen. Pravilo lahko premaknete ali odstranite in ga nadomestite z novim pravilom.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Obstoječa vrednost:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'To pravilo se vedno ujema. Pravila pod njim ne bodo dosežena.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'Za urejanje pravil mora biti omogočen JavaScript. Pri shranjevanju se ohranijo obstoječa pravila.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Uredi vprašanja';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Urejanje vprašanj (ID %s)';
