<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'Titre';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'Veuillez saisir le titre de la page.';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = 'Descriptif';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'Veuillez saisir la description de la page.';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = 'Dernier changement';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = 'Date et heure du dernier changement.';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'Introduction';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'Veuillez saisir une introduction de page. L\'introduction sera affichée au début de la page.';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'Modèle de page';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'Ici, vous pouvez sélectionner le modèle de page.';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = 'Nouvelle page';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = 'Créer une nouvelle page';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = 'Afficher les détails';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'Afficher les détails de la page ID %s';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'Déplacer la page';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'Déplacer l\'ID de page %s';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = 'Modifier la page';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'Modifier l\'ID de page %s';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = 'Page en double';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'ID de page en double %s';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'Supprimer la page';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'Supprimer l\'ID de page %s';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Pages';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Titre et description';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introduction';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Paramètres du modèle';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigation après cette page';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'Règles de navigation';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'Les règles sont évaluées en continuant après cette page.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'Les règles sont vérifiées de haut en bas. La première règle de correspondance détermine la page suivante. Toutes les conditions d\'une règle doivent correspondre. Les réponses manquantes ne satisfont jamais à une condition.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = 'Si aucune règle ne correspond, la page suivante suit. Une règle « Toujours » à la fin peut définir une destination par défaut différente.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = 'Filtrer les choix de questions';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = 'Rechercher par titre de question ou ID';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'Règle';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'Quand';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = 'Toutes les conditions suivantes correspondent (ET)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'Toujours';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = 'Question';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = 'Comparaison';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = 'Réponse';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'Puis continuez à';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = 'Veuillez sélectionner une question';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = 'Veuillez sélectionner une réponse';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'Veuillez sélectionner une page';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = 'Ajouter une autre règle';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'Supprimer la règle';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = 'Ajouter une autre condition ET';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = 'Supprimer la condition';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = 'Déplacer vers le haut';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = 'Déplacer vers le bas';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = 'contient la réponse';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = 'ne contient pas de réponse';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = 'est égal';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = 'n\'est pas égal';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'est supérieur à';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = 'est inférieur à';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = 'est supérieur ou égal à';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = 'est inférieur ou égal à';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'Veuillez vérifier toutes les règles de navigation : la question, la comparaison, la réponse et la destination doivent être valides. La destination doit être une autre page de la même enquête. Au maximum 100 règles comportant chacune 100 conditions sont autorisées.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'Cette règle existante contient une condition indisponible ou actuellement non prise en charge. Il est conservé inchangé. Vous pouvez déplacer ou supprimer la règle et la remplacer par une nouvelle règle.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = 'Valeur existante :';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'Cette règle correspond toujours. Les règles ci-dessous ne seront pas atteintes.';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'JavaScript doit être activé pour modifier les règles. Les règles existantes sont conservées lors de la sauvegarde.';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = 'Modifier les questions';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = 'Modifier les questions (ID %s)';
