<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'] = ['Title', 'Please enter the page title.'];
$GLOBALS['TL_LANG']['tl_survey_page']['description'] = ['Description', 'Please enter the page description.'];
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'] = ['Last change', 'Date and time of the last change.'];
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'] = ['Introduction', 'Please enter a page introduction. The introduction will be shown at the beginning of the page.'];
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'] = ['Page template', 'Here you can select the page template.'];

$GLOBALS['TL_LANG']['tl_survey_page']['new'] = ['New page', 'Create a new page'];
$GLOBALS['TL_LANG']['tl_survey_page']['show'] = ['Show details', 'Show details of page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['cut'] = ['Move page', 'Move page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['edit'] = ['Edit page', 'Edit page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['copy'] = ['Duplicate page', 'Duplicate page ID %s'];
$GLOBALS['TL_LANG']['tl_survey_page']['delete'] = ['Delete page', 'Delete page ID %s'];

$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'Page';

/*
* Legends
*/
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'Title and description';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'Introduction';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'Template settings';

$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'Navigation after this page';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'] = ['Navigation rules', 'Rules are evaluated when continuing after this page.'];
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor'] = [
    'help' => 'Rules are checked from top to bottom. The first matching rule determines the next page. All conditions within a rule must match. Missing answers never satisfy a condition.',
    'fallback' => 'If no rule matches, the next page in sequence follows. An “Always” rule at the end can define a different default destination.',
    'searchQuestions' => 'Filter question choices', 'searchHelp' => 'Search by question title or ID',
    'rule' => 'Rule', 'when' => 'When', 'all' => 'All the following conditions match (AND)', 'always' => 'Always',
    'question' => 'Question', 'operator' => 'Comparison', 'answer' => 'Answer', 'target' => 'Then continue to',
    'chooseQuestion' => 'Please select a question', 'chooseAnswer' => 'Please select an answer', 'choosePage' => 'Please select a page',
    'addRule' => 'Add another rule', 'removeRule' => 'Remove rule',
    'addCondition' => 'Add another AND condition', 'removeCondition' => 'Remove condition',
    'up' => 'Move up', 'down' => 'Move down',
    'contains' => 'contains answer', 'notContains' => 'does not contain answer',
    'operators' => ['=' => 'equals', '!=' => 'does not equal', '>' => 'is greater than', '<' => 'is less than', '>=' => 'is greater than or equal to', '<=' => 'is less than or equal to'],
    'invalid' => 'Please check all navigation rules: question, comparison, answer and destination must be valid. The destination must be another page of the same survey. At most 100 rules with 100 conditions each are allowed.',
    'legacy' => 'This existing rule contains an unavailable or currently unsupported condition. It is preserved unchanged. You can move or remove the rule and replace it with a new rule.',
    'legacyValue' => 'Existing value:',
    'unreachable' => 'This rule always matches. Rules below it will not be reached.',
    'javascript' => 'JavaScript must be enabled to edit rules. Existing rules are preserved when saving.',
];

$GLOBALS['TL_LANG']['tl_survey_page']['children'] = ['Edit questions', 'Edit questions (ID %s)'];
