<?php

namespace Hschottm\SurveyBundle\Export;

use Hschottm\SurveyBundle\Export\Exporter;
use Exporter\Handler;
use Exporter\Writer\CsvWriter;

class CSVExporter extends Exporter
{
  private $tempName;
  private $data = array();

  public function __construct($type = self::EXPORT_TYPE_XLS)
  {
    parent::__construct($type);
    $this->tempName = tempnam(sys_get_temp_dir(), 'CSV_EXPORT');
  }

  public function createSpreadsheet()
  {
    $this->spreadsheet = new CsvWriter($this->tempName, ',', '"', '', true, true);
  }

  public function setCellValue($sheet, $row, $col, $data)
  {
    if (array_key_exists($sheet, $this->sheets))
    {
      $celldata = array(
        self::ROW => $row,
        self::COL => $col
      );
      foreach ($data as $key => $value)
      {
        $celldata[$key] = $value;
      }
      if (!array_key_exists(self::CELLTYPE, $celldata)) $celldata[self::CELLTYPE] = self::CELLTYPE_STRING;
      if ($celldata[self::CELLTYPE] === self::CELLTYPE_STRING)
      {
        $celldata[self::DATA] = utf8_decode($celldata[self::DATA]);
      }
      $this->sheets[$sheet][$this->getCell($row, $col)] = $celldata;
      return true;
    }
    else {
      return false;
    }
  }

  protected function setSpreadsheetProperties($title = "", $subject = "", $description = "", $creator = "", $modificator = "")
  {
    /*
    $this->spreadsheet->getProperties()->setCreator($creator);
    $this->spreadsheet->getProperties()->setLastModifiedBy($creator);
    $this->spreadsheet->getProperties()->setTitle($title);
    $this->spreadsheet->getProperties()->setSubject($subject);
    $this->spreadsheet->getProperties()->setDescription($description);
    */
  }

  protected function send()
  {
    $this->spreadsheet->open();
    foreach ($this->data as $row)
    {
      $this->spreadsheet->write($row);
    }
    $this->spreadsheet->close();
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="' . \StringUtil::sanitizeFileName(htmlspecialchars_decode($this->filename)).'.csv' . '"');
    readfile($this->tempName);
    unlink($this->tempName);
    exit;
  }

  protected function setCellSpreadsheet($sheet, $cell)
  {
    $this->data[$cell[self::ROW]][$cell[self::COL]] = $cell[self::DATA];
  }

}