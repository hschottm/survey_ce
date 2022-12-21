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

$GLOBALS['TL_LANG']['tl_survey_question']['title'] = ['Title', 'Please enter the question title.'];
$GLOBALS['TL_LANG']['tl_survey_question']['alias'] = ['Alias', 'The question alias is a unique reference to the question which can be called instead of its numeric ID.'];
$GLOBALS['TL_LANG']['tl_survey_question']['author'] = ['Author', 'Please enter the name of the author.'];
$GLOBALS['TL_LANG']['tl_survey_question']['questiontype'] = ['Question type', 'Please choose the question type.'];
$GLOBALS['TL_LANG']['tl_survey_question']['description'] = ['Description', 'Please enter the question description.'];
$GLOBALS['TL_LANG']['tl_survey_question']['question'] = ['Question text', 'Please enter the question text.'];
$GLOBALS['TL_LANG']['tl_survey_question']['language'] = ['Language', 'Please choose the question language.'];
$GLOBALS['TL_LANG']['tl_survey_question']['obligatory'] = ['Mandatory', 'A mandatory question requires an answer.'];
$GLOBALS['TL_LANG']['tl_survey_question']['help'] = ['Help', 'Please enter a help text that is shown next to the question title.'];
$GLOBALS['TL_LANG']['tl_survey_question']['introduction'] = ['Introduction', 'Please enter an introduction that is shown at the beginning of a page.'];
$GLOBALS['TL_LANG']['tl_survey_question']['lower_bound'] = ['Lower bound', 'Please enter the lower bound of the range.'];
$GLOBALS['TL_LANG']['tl_survey_question']['upper_bound'] = ['Upper bound', 'Please enter the upper bound of the range.'];
$GLOBALS['TL_LANG']['tl_survey_question']['choices']['0'] = 'Choices';
$GLOBALS['TL_LANG']['tl_survey_question']['choices']['1'] = 'Please use the buttons to create, copy, move, or delete choices. If you disabled JavaScript, please save your input before you change the struture of the choices!';
$GLOBALS['TL_LANG']['tl_survey_question']['choices_'] = [
    'choice' => [
        'Choice',
    ],
    'category' => [
        'Category',
    ],
];

$GLOBALS['TL_LANG']['tl_survey_question']['hidetitle'] = ['Hide question title', 'Do not show the question title during survey execution.'];
$GLOBALS['TL_LANG']['tl_survey_question']['addother'] = ['Add other', 'Add an additional choice (other) with a text field.'];
$GLOBALS['TL_LANG']['tl_survey_question']['addscale'] = ['Add scale', 'Choose a scale from the list of scales and add the scale to the question.'];
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style'] = ['Answer presentation', 'Please choose an answer presentation.'];
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['vertical'] = 'Vertical aligned choices';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['horizontal'] = 'Horizontal aligned choices';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_style']['select'] = 'Dropdown menu';
$GLOBALS['TL_LANG']['tl_survey_question']['othertitle'] = ['Other title', 'Please enter a title for the additional choice. The text will be shown in front of the text field.'];
$GLOBALS['TL_LANG']['tl_survey_question']['scale'] = ['Scale', 'Please choose a scale from the list of scales.'];
$GLOBALS['TL_LANG']['tl_survey_question']['save_add_scale'] = 'Add scale';

$GLOBALS['TL_LANG']['tl_survey_question']['openended_subtype'] = ['Subtype', 'Please choose an openended question subtype.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textbefore'] = ['Label in front', 'Please enter a label that is shown in front of the text field.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textafter'] = ['Label behind', 'Please enter a label that is shown behind the text field.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_textinside'] = ['Placeholder', 'Please enter a placeholder text that is shown in the text field.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_rows'] = ['Rows', 'Please enter the number of rows for the text area.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_cols'] = ['Columns', 'Please enter the number of columns for the text area.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_width'] = ['Width', 'Please enter the width of the text field in characters.'];
$GLOBALS['TL_LANG']['tl_survey_question']['openended_maxlen'] = ['Maximum length', 'Please enter the maximum length of the text field in characters.'];

$GLOBALS['TL_LANG']['tl_survey_question']['multiplechoice_subtype'] = ['Subtype', 'Please choose a multiple choice question subtype.'];
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_subtype'] = ['Subtype', 'Please choose a matrix question subtype.'];
$GLOBALS['TL_LANG']['tl_survey_question']['matrixrows'] = ['Rows', 'Please use the buttons to create, copy, move, or delete rows. If you disabled JavaScript, please save your input before you change the struture of the rows!'];
$GLOBALS['TL_LANG']['tl_survey_question']['matrixcolumns'] = ['Columns', 'Please use the buttons to create, copy, move, or delete columns. If you disabled JavaScript, please save your input before you change the struture of the columns!'];
$GLOBALS['TL_LANG']['tl_survey_question']['addneutralcolumn'] = ['Add neutral column', 'Add a neutral column as last column (undecided, don\'t know, etc.) of the matrix question.'];
$GLOBALS['TL_LANG']['tl_survey_question']['neutralcolumn'] = ['Neutral column', 'Please enter the text for the neutral column.'];
$GLOBALS['TL_LANG']['tl_survey_question']['addbipolar'] = ['Show bipolar attributes', 'Show bipolar attributes for the matrix question (e.g. good - bad, light - heavy, etc.).'];
$GLOBALS['TL_LANG']['tl_survey_question']['adjective1'] = ['Left attribute', 'Please enter the text of the left attribute.'];
$GLOBALS['TL_LANG']['tl_survey_question']['adjective2'] = ['Right attribute', 'Please enter the text of the right attribute.'];
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition'] = ['Position attributes', 'Please choose the position of the bipolar attributes in the matrix question.'];
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['top'] = 'Above the column headers';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolarposition']['aside'] = 'Left and right of the columns';

$GLOBALS['TL_LANG']['tl_survey_question']['new'] = ['New question', 'Create a new question'];
$GLOBALS['TL_LANG']['tl_survey_question']['show'] = ['Details', 'Show details of question ID %s'];
$GLOBALS['TL_LANG']['tl_survey_question']['edit'] = ['Edit question', 'Edit question ID %s'];
$GLOBALS['TL_LANG']['tl_survey_question']['copy'] = ['Duplicate question', 'Duplicate question ID %s'];
$GLOBALS['TL_LANG']['tl_survey_question']['cut'] = ['Move question', 'Move question ID %s'];
$GLOBALS['TL_LANG']['tl_survey_question']['up'] = ['Move up', 'Move question ID %s up'];
$GLOBALS['TL_LANG']['tl_survey_question']['down'] = ['Move down', 'Move question ID %s down'];
$GLOBALS['TL_LANG']['tl_survey_question']['delete'] = ['Delete question', 'Delete question ID %s'];
$GLOBALS['TL_LANG']['tl_survey_question']['details'] = ['Detailed statistics', 'Show detailed statistics of question ID %s'];

$GLOBALS['TL_LANG']['tl_survey_question']['openended'] = 'Openended question';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_singleline'] = 'Single-line';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_multiline'] = 'Multi-line';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_integer'] = 'Integer';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_float'] = 'Floating point number';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_date'] = 'Date';
$GLOBALS['TL_LANG']['tl_survey_question']['oe_time'] = 'Time';
$GLOBALS['TL_LANG']['tl_survey_question']['multiplechoice'] = 'Multiple choice question';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_singleresponse'] = 'Single response';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_multipleresponse'] = 'Multiple response';
$GLOBALS['TL_LANG']['tl_survey_question']['mc_dichotomous'] = 'Dichotomous (Yes/No)';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix'] = 'Matrix question';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_singleresponse'] = 'One answer per row (single response)';
$GLOBALS['TL_LANG']['tl_survey_question']['matrix_multipleresponse'] = 'Multiple answers per row (multiple response)';
$GLOBALS['TL_LANG']['tl_survey_question']['constantsum'] = 'Constant sum';
$GLOBALS['TL_LANG']['tl_survey_question']['sum'] = ['Sum', 'Enter a sum value.'];
$GLOBALS['TL_LANG']['tl_survey_question']['sumoption'] = ['Calculation', 'Select an option to compare the entered values with the sum value.'];
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['exact'] = 'The sum of the entered values has to be equal to the sum value.';
$GLOBALS['TL_LANG']['tl_survey_question']['sum']['max'] = 'The sum of the entered values must not be greater than the sum value.';
$GLOBALS['TL_LANG']['tl_survey_question']['inputfirst'] = ['Show input fields in front', 'Show input fields in front of the answer text (default is behind the answer text).'];

$GLOBALS['TL_LANG']['tl_survey_question']['answered'] = 'Answered';
$GLOBALS['TL_LANG']['tl_survey_question']['skipped'] = 'Skipped';
$GLOBALS['TL_LANG']['tl_survey_question']['most_selected_value'] = 'Most selected value';
$GLOBALS['TL_LANG']['tl_survey_question']['nr_of_selections'] = 'Number of selections';
$GLOBALS['TL_LANG']['tl_survey_question']['median'] = 'Median';
$GLOBALS['TL_LANG']['tl_survey_question']['arithmeticmean'] = 'Arithmetic mean';
$GLOBALS['TL_LANG']['tl_survey_question']['yes'] = 'Yes';
$GLOBALS['TL_LANG']['tl_survey_question']['no'] = 'No';

$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_new'] = 'New answer';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_copy'] = 'Duplicate answer';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_delete'] = 'Delete answer';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_new'] = 'New row';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_copy'] = 'Duplicate row';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixrow_delete'] = 'Delete row';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_new'] = 'New column';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_copy'] = 'Duplicate column';
$GLOBALS['TL_LANG']['tl_survey_question']['buttontitle_matrixcolumn_delete'] = 'Delete column';
$GLOBALS['TL_LANG']['tl_survey_question']['cssClass'] = ['CSS class', 'Here you can enter one or more CSS classes.'];
$GLOBALS['TL_LANG']['tl_survey_question']['answers'] = 'Answers';
/*
* Legends
*/
$GLOBALS['TL_LANG']['tl_survey_question']['title_legend'] = 'Title and question type';
$GLOBALS['TL_LANG']['tl_survey_question']['question_legend'] = 'Question text';
$GLOBALS['TL_LANG']['tl_survey_question']['obligatory_legend'] = 'Mandatory input';
$GLOBALS['TL_LANG']['tl_survey_question']['specific_legend'] = 'Question specific settings';
$GLOBALS['TL_LANG']['tl_survey_question']['rows_legend'] = 'Matrix rows';
$GLOBALS['TL_LANG']['tl_survey_question']['columns_legend'] = 'Matrix columns';
$GLOBALS['TL_LANG']['tl_survey_question']['bipolar_legend'] = 'Bipolar attributes';
$GLOBALS['TL_LANG']['tl_survey_question']['sum_legend'] = 'Sum options';
$GLOBALS['TL_LANG']['tl_survey_question']['expert_legend'] = 'Expert settings';
