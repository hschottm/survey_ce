<?php

require __DIR__.'/bootstrap.php';

foreach (glob($root.'/src/Resources/contao/templates/*/*.twig') as $file) {
    $twig->load('@Contao/'.basename($file));
}
foreach ($map as $class => $file) {
    check(class_exists($class), 'Class does not load: '.$class);
}
$bundle = new Hschottm\SurveyBundle\HschottmSurveyBundle();
$bundle->getContainerExtension()->load([], $container);

$base = [
    'id' => 7, 'obligatory' => false, 'question' => '<p>Question</p>', 'title' => 'A & B',
    'help' => 'Help', 'hidetitle' => false, 'cssClass' => '',
];
$open = new Hschottm\SurveyBundle\FormOpenEndedQuestion();
$open->surveydata = $base + [
    'openended_subtype' => 'oe_multiline', 'openended_textbefore' => '', 'openended_textafter' => '',
    'openended_rows' => 3, 'openended_cols' => 20, 'openended_textinside' => '',
];
$open->value = '</textarea><script>alert(1)</script> & "';
$html = $open->generate();
check(str_contains($html, '&lt;/textarea&gt;') && !str_contains($html, '<script>'), 'Textarea must escape submitted text');
check(str_contains($html, 'name="question[7]"'), 'Question submission name changed');
$block = $twig->render('@Contao/survey_questionblock.html.twig', ['surveypage' => [$open]]);
check(str_contains($block, 'A &amp; B') && str_contains($block, '<p>Question</p>'), 'Widget magic properties or rich text lost');

foreach (['horizontal', 'vertical', 'select'] as $style) {
    foreach (['mc_singleresponse', 'mc_multipleresponse', 'mc_dichotomous'] as $type) {
        if ($style === 'select' && $type === 'mc_multipleresponse') continue;
        $widget = new Hschottm\SurveyBundle\FormMultipleChoiceQuestion();
        $widget->surveydata = $base + ['othertitle' => 'Other', 'addother' => true, 'mc_style' => $style, 'choices' => serialize(['One', 'Two']), 'multiplechoice_subtype' => $type];
        $widget->value = ['value' => $type === 'mc_multipleresponse' ? [1 => '1'] : '1', 'other' => ''];
        $html = $widget->generate();
        check(str_contains($html, $style === 'select' ? 'selected="selected"' : 'checked="checked"'), 'Multiple choice selection lost: '.$type.'/'.$style);
        check(!str_contains($html, '&lt;input'), 'Widget HTML was escaped');
    }
}
foreach (['matrix_singleresponse', 'matrix_multipleresponse'] as $type) {
    $matrix = new Hschottm\SurveyBundle\FormMatrixQuestion();
    $matrix->surveydata = $base + ['matrixrows' => serialize(['Row A', 'Row B']), 'matrixcolumns' => serialize(['Yes', 'No']), 'matrix_subtype' => $type, 'addneutralcolumn' => true, 'addbipolar' => false, 'neutralcolumn' => 'Neither', 'adjective1' => '', 'adjective2' => '', 'bipolarposition' => 'top'];
    $matrix->value = $type === 'matrix_singleresponse' ? [1 => '1', 2 => '3'] : [1 => [1 => '1'], 2 => [3 => '3']];
    $html = $matrix->generate();
    check(substr_count($html, 'checked="checked"') === 2, 'Matrix selections/neutral column lost');
    check(str_contains($html, 'ctrl_7_2_3'), 'Matrix indices changed');
}
$sum = new Hschottm\SurveyBundle\FormConstantSumQuestion();
$sum->surveydata = $base + ['sumoption' => 'exact', 'sum' => 100, 'inputfirst' => false, 'sumchoices' => serialize(['One', 'Two'])];
$sum->value = [1 => 40, 2 => 60];
check(str_contains($sum->generate(), 'value="60"'), 'Constant sum value lost');
$validator = new ReflectionMethod($sum, 'validator');
$validator->invoke($sum, [1 => '40', 2 => '60']);
check(!$sum->hasErrors(), 'Valid sum rejected');
$validator->invoke($sum, [1 => 'not a number', 2 => '60']);
check($sum->hasErrors(), 'Invalid sum must be a validation error, not a PHP TypeError');

$context = ['class' => 'ce_survey', 'cssID' => '', 'style' => '', 'headline' => '', 'protected' => false, 'pages' => [['title' => 'Page', 'introduction' => '<p>Intro</p>']], 'page' => 0, 'introduction' => '<p>Welcome</p>', 'show_title' => true, 'surveytitle' => 'Survey', 'action' => '/survey', 'start' => 'Start', 'pageXofY' => 'Page %s of %s'];
foreach (['ce_survey', 'ce_survey_blue_smileys'] as $template) {
    $html = $twig->render('@Contao/'.$template.'.html.twig', $context);
    check(str_contains($html, 'test-token') && str_contains($html, '<p>Welcome</p>'), 'Start form/token broken');
    $html = $twig->render('@Contao/'.$template.'.html.twig', array_replace($context, ['page' => 1, 'questionblock' => $block, 'finish' => 'Finish']));
    check(str_contains($html, 'Page 1 of 1') && str_contains($html, 'name="finish"'), 'Question page/progress broken');
    $html = $twig->render('@Contao/'.$template.'.html.twig', array_replace($context, ['page' => 2, 'finalsubmission' => '<p>Thanks</p>']));
    check(str_contains($html, '<p>Thanks</p>') && !str_contains($html, 'name="finish"'), 'Completion page broken');
}
echo "PASS: 23 Twig templates, all classes, DI configuration, four question types, selections, escaping, rich text, validation and survey navigation markup.\n";

$GLOBALS['TL_CTE']['includes'] = [];
$GLOBALS['BE_MOD'] = [];
$GLOBALS['BE_FFL'] = [];
require $root.'/src/Resources/contao/config/config.php';
check(count($GLOBALS['TL_MODELS']) === 8, 'Namespaced models are not registered');

$backendWidget = new class {
    public $help = 'Help';
    public $required = true;
    public function generateLabel() { return '<label>Number</label>'; }
    public function generateWithError() { return '<input name="amount" value="5">'; }
};
foreach (['be_survey_create_tan' => 'nrOfTAN', 'be_survey_export_tan' => 'surveyPage'] as $template => $variable) {
    $html = $twig->render('@Contao/'.$template.'.html.twig', [$variable => $backendWidget, 'request' => '/contao', 'headline' => 'TAN', 'submit' => 'Save']);
    check(str_contains($html, 'test-token') && str_contains($html, '<input name="amount"'), 'Backend TAN form/token broken');
}
foreach ([
    'survey_answers_default' => ['answers' => ['<script>unsafe</script>']],
    'survey_answers_multiplechoice' => ['choices' => ['A', 'B'], 'statistics' => ['cumulated' => [1 => 2, 2 => 3]]],
    'survey_answers_matrix' => ['rows' => ['R'], 'choices' => ['A', 'B'], 'statistics' => ['cumulated' => [1 => [1 => 2, 2 => 3]]]],
    'survey_answers_constantsum' => ['choices' => ['A'], 'cumulated' => [1 => [40 => 2]]],
] as $template => $data) {
    $html = $twig->render('@Contao/'.$template.'.html.twig', $data);
    check(!str_contains($html, '<script>'), 'Report renders user data as executable HTML');
    check(str_contains($html, $template === 'survey_answers_default' ? '&lt;script&gt;' : '<td class="selections">2</td>'), 'Report aggregation indices changed: '.$template);
}

if (class_exists(PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
    $exporter = new class extends Hschottm\SurveyBundle\Export\ExcelExporterPhpSpreadsheet {
        protected function send() {}
    };
    $exporter->addSheet('Results');
    $exporter->setCellValue('Results', 0, 0, ['data' => '001234']);
    $exporter->setCellValue('Results', 1, 0, ['data' => '=1+1']);
    $exporter->setCellValue('Results', 2, 0, ['data' => 12.5, 'type' => 'f']);
    $exporter->sendFile();
    $sheet = $exporter->getSpreadsheet()->getSheetByName('Results');
    check($sheet->getCell('A1')->getValue() === '001234', 'Export loses leading zeroes or shifts first row');
    check($sheet->getCell('A2')->getDataType() === 's', 'User text interpreted as Excel formula');
    check($sheet->getCell('A3')->getValue() === 12.5, 'Numeric export broken');
}
echo "PASS: model registration, backend TAN forms, result reports and optional Excel exporter.\n";

$process = proc_open([PHP_BINARY, __DIR__.'/fixtures/csv.php'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
$csv = stream_get_contents($pipes[1]);
$error = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
check(proc_close($process) === 0 && $error === '', 'CSV export failed: '.$error);
check(str_getcsv(trim($csv), ',', '"', '') === ['001234', '', 'Grüße, "Welt"'], 'CSV encoding, column positions or leading zeroes lost');
echo "PASS: CSV export including UTF-8, quoting and sparse columns.\n";

foreach (glob($root.'/src/Resources/contao/languages/en/*.php') as $file) {
    require $file;
}
$GLOBALS['TL_LANG']['MSC']['deleteConfirm'] = 'Delete?';
$GLOBALS['TL_LANG']['MSC']['delAllConfirm'] = 'Delete all?';
foreach (glob($root.'/src/Resources/contao/dca/*.php') as $file) {
    require $file;
}
check($GLOBALS['TL_DCA']['tl_survey']['fields']['online_start']['eval']['datepicker'] === true, 'Survey date picker not configured');
check($GLOBALS['TL_DCA']['tl_survey_question']['fields']['lower_bound_date']['eval']['datepicker'] === true, 'Question date picker not configured');
echo "PASS: all DCA files load without removed Contao helper methods.\n";

foreach ($GLOBALS['TL_DCA'] as $table => $dca) {
    if (!str_starts_with($table, 'tl_survey') || !isset($dca['config']['dataContainer'])) {
        continue;
    }
    $driver = $dca['config']['dataContainer'];
    check(class_exists($driver) && is_subclass_of($driver, Contao\DataContainer::class), 'Unresolvable data container for '.$table.': '.$driver);
}
echo "PASS: all Survey data container classes resolve under Contao 5.7.\n";

// Exercise stored answers directly without a database or backend request.
foreach (['mc_singleresponse', 'mc_dichotomous', 'matrix_singleresponse'] as $subtype) {
    $matrix = str_starts_with($subtype, 'matrix_');
    $class = new ReflectionClass($matrix ? Hschottm\SurveyBundle\SurveyQuestionMatrix::class : Hschottm\SurveyBundle\SurveyQuestionMultiplechoice::class);
    $question = $class->newInstanceWithoutConstructor();
    $values = ['', null, 'invalid', '0', '99', [], '2'];
    $statistics = ['answered' => 1, 'skipped' => 6, 'participants' => []];
    foreach ($values as $id => $value) {
        $result = ['result' => serialize($matrix ? [1 => $value] : ['value' => $value])];
        $statistics['participants'][$id] = $id % 2 ? [$result] : $result;
    }
    foreach ([
        'arrData' => [($matrix ? 'matrix_subtype' : 'multiplechoice_subtype') => $subtype, 'addother' => false],
        'arrStatistics' => $statistics,
        'choices' => ['A', 'B'],
    ] as $property => $value) {
        $class->getProperty($property)->setValue($question, $value);
    }
    if ($matrix) {
        $class->getProperty('subquestions')->setValue($question, ['Row']);
    }
    $exporter = new class {
        public array $cells = [];
        public function setCellValue($sheet, $row, $col, $cell) {
            $this->cells[$row][$col] = $cell['data'];
        }
    };
    $row = 8;
    $col = 3;
    $participants = array_fill(0, count($values) + 1, []);
    $method = $class->getMethod('exportDetailResults');
    $method->invokeArgs($question, [&$exporter, 'Results', &$row, &$col, $participants]);
    check($exporter->cells === [14 => [3 => 'B']], 'Skipped/invalid answers or row alignment broken: '.$subtype);
    check($row === 16, 'Missing participant changes export row alignment: '.$subtype);
}
echo "PASS: detailed exports preserve empty answers and participant alignment for selection and matrix questions.\n";

if (class_exists(PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
    foreach (['de' => 'Datum', 'en' => 'Date'] as $language => $dateLabel) {
        require $root.'/src/Resources/contao/languages/'.$language.'/tl_survey_result.php';
        $exporter = new class extends Hschottm\SurveyBundle\Export\ExcelExporterPhpSpreadsheet {
            protected function send() {}
        };
        $exporter->addSheet('Results');
        $class = new ReflectionClass(Hschottm\SurveyBundle\SurveyResultDetails::class);
        $details = $class->newInstanceWithoutConstructor();
        $class->getMethod('exportTopLeftArea')->invokeArgs($details, [&$exporter, 'Results']);
        $row = 8;
        $col = 0;
        $participants = ['test' => ['id' => 123, 'count' => 1, 'date' => '2026-10-22 12:00:00', 'lastpage' => 10, 'finished' => true, 'display' => 'Test']];
        $class->getMethod('exportParticipantRowHeaders')->invokeArgs($details, [&$exporter, 'Results', &$row, &$col, $participants]);
        $exporter->setCellValue('Results', 0, 5, ['data' => 'Width', 'cw' => 20 * 256, 'tw' => false]);
        $exporter->sendFile();
        $sheet = $exporter->getSpreadsheet()->getSheetByName('Results');
        check($sheet->getCell('B7')->getValue() === 'Sort', 'Sequence header overwritten: '.$language);
        check($sheet->getCell('C7')->getValue() === $dateLabel, 'Date header missing: '.$language);
        check($sheet->getCell('A7')->getValue() === 'ID' && $sheet->getCell('E7')->getValue() === $GLOBALS['TL_LANG']['tl_survey_result']['ex_question_participant'], 'Participant headings must replace the title legend in row 7');
        check($sheet->getCell('A8')->getValue() === '' && $sheet->getCell('E8')->getValue() === '', 'Row 8 must remain available for question subheadings');
        check($sheet->getCell('B9')->getValue() == 1 && $sheet->getCell('C9')->getValue() === '2026-10-22 12:00:00', 'Participant columns shifted');
        check($sheet->getStyle('A9')->getNumberFormat()->getFormatCode() === '0', 'Participant ID has decimal places');
        check(!$sheet->getStyle('F1')->getAlignment()->getWrapText(), 'Disabled wrapping ignored');
        check($sheet->getColumnDimension('F')->getWidth() === 20.0, 'Legacy column width units not converted');
    }
}
echo "PASS: German/English export headers, participant columns, integer IDs and XLSX column widths.\n";

if (class_exists(PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
    foreach (['', 'Option A', '0'] as $subheading) {
        $exporter = new class extends Hschottm\SurveyBundle\Export\ExcelExporterPhpSpreadsheet {
            protected function send() {}
        };
        $exporter->addSheet('Results');
        $e = Hschottm\SurveyBundle\Export\Exporter::class;
        foreach ([0 => 'ID', 5 => 'Question'] as $column => $title) {
            $exporter->setCellValue('Results', 6, $column, [$e::DATA => $title]);
            $exporter->setCellValue('Results', 7, $column, [
                $e::DATA => $column === 5 ? $subheading : '',
                $e::BORDERBOTTOM => $e::BORDER_THIN,
                $e::BORDERBOTTOMCOLOR => '#000000',
            ]);
            $exporter->setCellValue('Results', 8, $column, [$e::DATA => 'First']);
            $exporter->setCellValue('Results', 9, $column, [$e::DATA => 'Second']);
        }
        $exporter->omitEmptySubheadingRow('Results', 7);
        $exporter->sendFile();
        $sheet = $exporter->getSpreadsheet()->getSheetByName('Results');
        $firstRow = $subheading === '' ? 8 : 9;
        foreach (['A', 'F'] as $column) {
            check($sheet->getCell($column.$firstRow)->getValue() === 'First', 'First participant misaligned after header compaction');
            check($sheet->getCell($column.($firstRow + 1))->getValue() === 'Second', 'Second participant misaligned after header compaction');
            check($sheet->getStyle($column.($firstRow - 1))->getBorders()->getBottom()->getBorderStyle() === 'thin', 'Header separator lost');
        }
        if ($subheading !== '') {
            check($sheet->getCell('F8')->getValue() === $subheading, 'Required subheading removed');
        }
    }
}
echo "PASS: empty subheading row omitted, populated/zero subheadings retained, participant alignment and separators preserved.\n";

if (class_exists(PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
    foreach ([false, true] as $hasSubheading) {
        $exporter = new class extends Hschottm\SurveyBundle\Export\ExcelExporterPhpSpreadsheet {
            protected function send() {}
        };
        $e = Hschottm\SurveyBundle\Export\Exporter::class;
        $exporter->addSheet('Results');
        for ($r = 0; $r < 6; ++$r) {
            $exporter->setCellValue('Results', $r, 4, [$e::DATA => 'Statistic '.$r]);
            $exporter->setCellValue('Results', $r, 5, [$e::DATA => $r + 10, $e::MERGE => 'F'.($r + 1).':G'.($r + 1)]);
        }
        $exporter->setCellValue('Results', 6, 0, [$e::DATA => 'ID']);
        $exporter->setCellValue('Results', 6, 5, [$e::DATA => 'Question', $e::MERGE => 'F7:G7']);
        foreach ([0, 5, 6] as $c) {
            $exporter->setCellValue('Results', 7, $c, [$e::DATA => $hasSubheading && $c > 0 ? 'Option '.$c : '', $e::BORDERBOTTOM => $e::BORDER_THIN]);
            $exporter->setCellValue('Results', 8, $c, [$e::DATA => 'Answer '.$c]);
        }
        $exporter->omitEmptySubheadingRow('Results', 7);
        $exporter->separateQuestionOverview('Results', 'Fragenübersicht', 'Titel');
        $exporter->sendFile();
        // Round-trip the actual XLSX to verify sheet order and relocated merged cells.
        $file = tempnam(sys_get_temp_dir(), 'survey-xlsx-');
        try {
            (new PhpOffice\PhpSpreadsheet\Writer\Xlsx($exporter->getSpreadsheet()))->save($file);
            $book = PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            check($book->getSheetNames() === ['Results', 'Fragenübersicht'], 'Unexpected worksheet order');
            $results = $book->getSheetByName('Results');
            $overview = $book->getSheetByName('Fragenübersicht');
            check($results->getCell('A1')->getValue() === 'ID' && $results->getCell('F1')->getValue() === 'Question', 'Result headings not moved to first row');
            check(in_array('F1:G1', $results->getMergeCells(), true), 'Result title merge not relocated');
            check($results->getCell('F'.($hasSubheading ? 3 : 2))->getValue() === 'Answer 5', 'Answers shifted incorrectly');
            check($overview->getCell('A1')->getValue() === 'Statistic 0' && $overview->getCell('B7')->getValue() === 15, 'Statistics not transferred');
            check($overview->getCell('A2')->getValue() === 'Titel:' && $overview->getCell('B2')->getValue() === 'Question', 'Overview question titles missing');
            check(in_array('B2:C2', $overview->getMergeCells(), true), 'Overview title merge not relocated');
            foreach (['B1', 'B3', 'B6', 'B7'] as $coordinate) {
                check($overview->getStyle($coordinate)->getNumberFormat()->getFormatCode() === '0', 'Overview counts and IDs must use integer format');
                check($overview->getCell($coordinate)->getDataType() === 'n', 'Overview counts and IDs must remain numeric');
            }
            check($overview->getStyle('A2')->getFont()->getBold(), 'Overview title label must be bold');
            check($overview->getStyle('A2')->getFill()->getStartColor()->getARGB() === 'FFC0C0C0', 'Overview title label must be grey');
            check($overview->getStyle('B2')->getBorders()->getBottom()->getBorderStyle() === 'none', 'Overview must not inherit result separator');
            check($results->getStyle('F'.($hasSubheading ? 2 : 1))->getBorders()->getBottom()->getBorderStyle() === 'thin', 'Result separator must remain');

        } finally {
            unlink($file);
        }
    }
}
echo "PASS: XLSX overview sheet and compact result headers survive save/load with and without subheadings.\n";

require __DIR__.'/conditions.php';

foreach (['tl_survey' => 'tl_survey_page', 'tl_survey_page' => 'tl_survey_question'] as $table => $child) {
    $operations = $GLOBALS['TL_DCA'][$table]['list']['operations'];
    check($operations['edit']['href'] === 'act=edit' && $operations['edit']['showInHeader'] === true, 'Header must edit the record, not open its child list');
    check($operations['children']['href'] === 'table='.$child && $operations['children']['showInHeader'] === false, 'Child navigation must be separate from header editing');
}
echo "PASS: survey/page edit actions are separate from child navigation.\n";
