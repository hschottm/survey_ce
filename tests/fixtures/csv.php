<?php
require dirname(__DIR__).'/bootstrap.php';
$exporter = new Hschottm\SurveyBundle\Export\CSVExporter();
$exporter->addSheet('Results');
$exporter->setCellValue('Results', 0, 2, ['data' => 'Grüße, "Welt"']);
$exporter->setCellValue('Results', 0, 0, ['data' => '001234']);
$exporter->sendFile();
