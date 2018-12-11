<?php

namespace Hschottm\SurveyBundle\Export;

use Hschottm\SurveyBundle\Export\ExcelExporter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ExcelExporterPhpSpreadsheet extends ExcelExporter
{
  public function __construct($type = self::EXPORT_TYPE_XLS)
  {
    parent::__construct($type);
  }

  public function webColorToARGB($color)
  {
    $col = str_replace('#', '', $color);
    if (strlen($col) == 6)
    {
      return "ff" . $col;
    }
    else if (strlen($color) == 8)
    {
      return $col;
    }
    return "ff000000";
  }

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
      $this->sheets[$sheet][$this->getCell($row, $col)] = $celldata;
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
    $objWriter = new Xlsx($this->spreadsheet);
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
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_GENERAL);
            break;
          case self::ALIGNMENT_H_LEFT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            break;
          case self::ALIGNMENT_H_CENTER:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            break;
          case self::ALIGNMENT_H_RIGHT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            break;
          case self::ALIGNMENT_H_FILL:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL);
            break;
          case self::ALIGNMENT_H_JUSTIFY:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);
            break;
          case self::ALIGNMENT_H_CENTER_CONT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER_CONTINUOUS);
            break;
      }
    }

    if (array_key_exists(self::TEXTROTATE, $cell))
    {
      switch ($cell[self::TEXTROTATE])
      {
        case self::TEXTROTATE_CLOCKWISE:
          $worksheet->getStyle($pos)->getAlignment()->setTextRotation(90);
          break;
        case self::TEXTROTATE_COUNTERCLOCKWISE:
          $worksheet->getStyle($pos)->getAlignment()->setTextRotation(-90);
          break;
        case self::TEXTROTATE_NONE:
          break;
        default:
          $worksheet->getStyle($pos)->getAlignment()->setTextRotation($cell[self::TEXTROTATE]);
          break;
      }
    }

    if (array_key_exists(self::TEXTWRAP, $cell))
    {
      if ($cell[self::TEXTWRAP]) $data['textwrap'] = '1';
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

    if (array_key_exists(self::BORDERBOTTOM, $cell))
    {
      switch ($cell[self::BORDERBOTTOM])
      {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          break;
        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
          break;
        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
          break;
        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);
          break;
        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
          break;
        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
          break;
        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED);
          break;
        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT);
          break;
        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOTDOT);
          break;
        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
          break;
        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOTDOT);
          break;
        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
          break;
      }
    }

    if (array_key_exists(self::BORDERBOTTOMCOLOR, $cell))
    {
      $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($this->webColorToARGB($cell[self::BORDERBOTTOMCOLOR])));
    }

    if (array_key_exists(self::BORDERTOP, $cell))
    {
      switch ($cell[self::BORDERTOP])
      {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          break;
        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
          break;
        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
          break;
        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);
          break;
        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
          break;
        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
          break;
        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED);
          break;
        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT);
          break;
        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOTDOT);
          break;
        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
          break;
        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOTDOT);
          break;
        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
          break;
      }
    }

    if (array_key_exists(self::BORDERTOPCOLOR, $cell))
    {
      $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($this->webColorToARGB($cell[self::BORDERTOPCOLOR])));
    }

    if (array_key_exists(self::BORDERLEFT, $cell))
    {
      switch ($cell[self::BORDERLEFT])
      {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          break;
        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
          break;
        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
          break;
        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);
          break;
        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
          break;
        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
          break;
        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED);
          break;
        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT);
          break;
        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOTDOT);
          break;
        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
          break;
        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOTDOT);
          break;
        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
          break;
      }
    }

    if (array_key_exists(self::BORDERLEFTCOLOR, $cell))
    {
      $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($this->webColorToARGB($cell[self::BORDERLEFTCOLOR])));
    }

    if (array_key_exists(self::BORDERRIGHT, $cell))
    {
      switch ($cell[self::BORDERRIGHT])
      {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          break;
        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR);
          break;
        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
          break;
        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);
          break;
        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);
          break;
        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
          break;
        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED);
          break;
        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT);
          break;
        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOTDOT);
          break;
        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
          break;
        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOT);
          break;
        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHDOTDOT);
          break;
        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
          break;
      }
    }

    if (array_key_exists(self::BORDERRIGHTCOLOR, $cell))
    {
      $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($this->webColorToARGB($cell[self::BORDERRIGHTCOLOR])));
    }

    if (array_key_exists(self::MERGE, $cell))
    {
      $worksheet->mergeCells($cell[self::MERGE]);
    }

    if (array_key_exists(self::COLWIDTH, $cell))
    {
      if ($cell[self::COLWIDTH] === self::COLWIDTH_AUTO)
      {
        $worksheet->getColumnDimension($this->getColumnIndex($cell[self::COL]))->setAutoSize(true);
      }
      else {
        $worksheet->getColumnDimension($this->getColumnIndex($cell[self::COL]))->setWidth($cell[self::COLWIDTH]);
      }
    }
  }

}
