<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Titolo';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Inserisci il titolo della pagina.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Descrizione';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Inserisci la descrizione della pagina.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Ultimo cambiamento';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Data e ora dell\'ultima modifica.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Introduzione';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Inserisci una pagina di introduzione. L\'introduzione verrà mostrata all\'inizio della pagina.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Modello di pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Qui puoi selezionare il modello di pagina.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nuova pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Crea una nuova pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Mostra dettagli';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Mostra i dettagli dell\'ID pagina %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Sposta pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Sposta l\'ID pagina %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Modifica pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Modifica l\'ID pagina %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Pagina duplicata';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'ID pagina duplicato %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Elimina pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Elimina l\'ID pagina %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titolo e descrizione';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introduzione';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Impostazioni del modello';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigazione dopo questa pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Regole di navigazione';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Le regole vengono valutate quando si continua dopo questa pagina.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Le regole vengono controllate dall\'alto verso il basso. La prima regola di corrispondenza determina la pagina successiva. Tutte le condizioni all\'interno di una regola devono corrispondere. Le risposte mancanti non soddisfano mai una condizione.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Se nessuna regola corrisponde, segue la pagina successiva in sequenza. Una regola "Sempre" alla fine può definire una destinazione predefinita diversa.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtra le scelte delle domande';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Cerca per titolo o ID della domanda';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Regola';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'Quando';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Tutte le seguenti condizioni corrispondono (AND)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Sempre';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Domanda';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Confronto';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Rispondi';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Quindi continua a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Seleziona una domanda';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Seleziona una risposta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Seleziona una pagina';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Aggiungi un\'altra regola';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Rimuovi regola';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Aggiungi un\'altra condizione AND';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Rimuovi condizione';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Sposta su';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Sposta giù';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'contiene la risposta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'non contiene risposta';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'è uguale';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'non è uguale';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'è maggiore di';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'è inferiore a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'è maggiore o uguale a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'è inferiore o uguale a';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Controlla tutte le regole di navigazione: domanda, confronto, risposta e destinazione devono essere valide. La destinazione deve essere un\'altra pagina dello stesso sondaggio. Sono consentite al massimo 100 regole con 100 condizioni ciascuna.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Questa regola esistente contiene una condizione non disponibile o attualmente non supportata. Si conserva inalterato. Puoi spostare o rimuovere la regola e sostituirla con una nuova regola.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Valore esistente:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Questa regola corrisponde sempre. Le regole sottostanti non verranno raggiunte.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'JavaScript deve essere abilitato per modificare le regole. Le regole esistenti vengono mantenute durante il salvataggio.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Modifica domande';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Modifica domande (ID %s)';
