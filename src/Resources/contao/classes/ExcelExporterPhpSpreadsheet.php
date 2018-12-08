<?php

namespace Hschottm\SurveyBundle;

use Hschottm\SurveyBundle\ExcelExporter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExporterPhpSpreadsheet extends ExcelExporter
{
  public function createSpreadsheet()
  {
    /*
    if ($this->type === self::EXPORT_TYPE_XLS)
    {
      $this->spreadsheet = new xlsexport();
    }
    */
    $this->spreadsheet = new Spreadsheet();
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
      /*
      if ($this->type === self::EXPORT_TYPE_XLS && $celldata[self::CELLTYPE] === self::CELLTYPE_STRING)
      {
        $celldata[self::DATA] = utf8_decode($celldata[self::DATA]);
      }
      */
      $this->sheets[$this->getCell($row, $col)] = $celldata;
      return true;
    }
    else {
      return false;
    }
  }

  protected function setSpreadsheetProperties($title = "", $subject = "", $description = "", $creator = "", $modificator = "")
  {
    $this->spreadsheet->getProperties()->setCreator($creator);
    $this->spreadsheet->getProperties()->setLastModifiedBy($creator);
    $this->spreadsheet->getProperties()->setTitle($title);
    $this->spreadsheet->getProperties()->setSubject($subject);
    $this->spreadsheet->getProperties()->setDescription($description);
  }

  protected function send()
  {
    $objWriter = new Xlsx($objPHPExcel);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.\StringUtil::sanitizeFileName(htmlspecialchars_decode($this->filename)).'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');
    echo "";
    exit;
  }

  protected function setCellSpreadsheet($sheet, $cell)
  {
    $pos = $this->getCell($cell[self::ROW]+1, $cell[self::COL]);
    $worksheet = $this->spreadsheet->getSheetByName($sheet);
    if (null == $worksheet)
    {
      $worksheet = $this->spreadsheet->addSheet(new Worksheet($this->spreadsheet, $sheet));
    }

    $worksheet->setCellValue($pos, $cell[self::DATA]);
    $worksheet->getColumnDimension($col)->setAutoSize(true);

    $fill_array = array();
    $font_array = array();

    switch ($cell[self::CELLTYPE])
    {
      case CELLTYPE_STRING:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        break;
      case CELLTYPE_FLOAT:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
        break;
      case CELLTYPE_PICTURE:
        break;
      case CELLTYPE_INTEGER:
      default:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        break;
    }

    if (array_key_exists(self::BGCOLOR, $cell))
    {
      $fill_array = array(
            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => str_replace('#', '', $cell[self::BGCOLOR]))
      );
    }
    if (array_key_exists(self::COLOR, $cell))
    {
      $font_array['color'] = array('rgb' => str_replace('#', '', $cell[self::COLOR]));
    }

    if (array_key_exists(self::ALIGNMENT, $cell))
    {
      switch ($cell[self::ALIGNMENT])
      {
        case self::ALIGNMENT_H_GENERAL:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_GENERAL);
            break;
          case self::ALIGNMENT_H_LEFT:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            break;
          case self::ALIGNMENT_H_CENTER:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            break;
          case self::ALIGNMENT_H_RIGHT:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            break;
          case self::ALIGNMENT_H_FILL:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL);
            break;
          case self::ALIGNMENT_H_JUSTIFY:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
            break;
          case self::ALIGNMENT_H_CENTER_CONT:
            $worksheet->->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER_CONTINUOUS);
            break;
      }
    }

    if (array_key_exists(self::FONTWEIGHT, $cell))
    {
      switch ($cell[self::FONTWEIGHT])
      {
        case self::FONTWEIGHT_BOLD:
          $font_array['bold'] = true;
          break;
      }
    }

    $worksheet->getStyle($pos)->applyFromArray(
      array(
        'fill' => $fill_array,
        'font' => $font_array
      )
    );
  }

}
