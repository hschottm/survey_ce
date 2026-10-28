<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Tytuł';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Proszę wpisać tytuł strony.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Opis';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Proszę wprowadzić opis strony.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Ostatnia zmiana';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Data i godzina ostatniej zmiany.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Wprowadzenie';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Proszę wprowadzić wprowadzenie strony. Wprowadzenie zostanie wyświetlone na początku strony.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Szablon strony';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Tutaj możesz wybrać szablon strony.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nowa strona';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Utwórz nową stronę';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Pokaż szczegóły';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Pokaż szczegóły identyfikatora strony %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Przenieś stronę';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Przenieś identyfikator strony %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Edytuj stronę';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Edytuj identyfikator strony %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Zduplikowana strona';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'Zduplikowany identyfikator strony %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Usuń stronę';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Usuń identyfikator strony %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Strona';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Tytuł i opis';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Wprowadzenie';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Ustawienia szablonu';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Nawigacja po tej stronie';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Zasady nawigacji';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Reguły są oceniane podczas kontynuowania po tej stronie.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Reguły sprawdzane są od góry do dołu. Pierwsza pasująca reguła określa następną stronę. Wszystkie warunki w regule muszą być zgodne. Brakujące odpowiedzi nigdy nie spełniają warunku.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Jeśli żadna reguła nie pasuje, następuje następna strona w kolejności. Reguła „Zawsze” na końcu może zdefiniować inne domyślne miejsce docelowe.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtruj opcje pytań';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Szukaj według tytułu pytania lub identyfikatora';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Reguła';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'Kiedy';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Wszystkie poniższe warunki są zgodne (ORAZ)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Zawsze';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Pytanie';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Porównanie';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Odpowiedź';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Następnie kontynuuj';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Wybierz pytanie';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Wybierz odpowiedź';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Wybierz stronę';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Dodaj kolejną regułę';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Usuń regułę';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Dodaj kolejny warunek AND';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Usuń warunek';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Przenieś w górę';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Przenieś w dół';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'zawiera odpowiedź';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'nie zawiera odpowiedzi';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'równa się';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'nie równa się';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'jest większy niż';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'jest mniej niż';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'jest większe lub równe';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'jest mniejsza lub równa';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Sprawdź wszystkie zasady nawigacji: pytanie, porównanie, odpowiedź i cel podróży muszą być prawidłowe. Miejscem docelowym musi być inna strona tej samej ankiety. Dozwolonych jest maksymalnie 100 reguł po 100 warunków każda.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Ta istniejąca reguła zawiera niedostępny lub obecnie nieobsługiwany warunek. Jest zachowany w niezmienionej formie. Możesz przenieść lub usunąć regułę i zastąpić ją nową.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Istniejąca wartość:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Ta zasada zawsze się sprawdza. Poniższe zasady nie zostaną osiągnięte.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'Aby móc edytować reguły, musi być włączona obsługa JavaScript. Istniejące reguły zostaną zachowane podczas zapisywania.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Edytuj pytania';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Edytuj pytania (ID %s)';
