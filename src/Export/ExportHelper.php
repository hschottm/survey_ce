<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle\Export;

use Hschottm\SurveyBundle\Export\Exporter;
use Hschottm\SurveyBundle\Export\ExcelExporterPhpSpreadsheet;
use Hschottm\SurveyBundle\Export\ExcelExporterXLSExport;
use Hschottm\SurveyBundle\Export\CSVExporter;

class ExportHelper
{
  public static function getExporter()
  {
    $exporter = null;
    if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
      $exporter = new ExcelExporterPhpSpreadsheet();
    } else if (class_exists('Hschottm\ExcelXLSBundle\xlsexport')) {
        $exporter = new ExcelExporterXLSExport();
    } else {
      $exporter = new CSVExporter();
    }
    return $exporter;
  }
}
