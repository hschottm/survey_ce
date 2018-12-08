<?php

namespace Hschottm\SurveyBundle;

use Hschottm\ExcelXLSBundle\xlsexport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelExporter
{
  const EXPORT_TYPE_XLS = 'xls';
  const EXPORT_TYPE_XLSX = 'xlsx';

  const ROW = 'r';
  const COL = 'c';
  const DATA = 'data';
  const COLOR = 'col';
  const BGCOLOR = 'bgcol';
  const CELLTYPE = 'type';
  const CELLTYPE_STRING = 's';
  const CELLTYPE_FLOAT = 'f';
  const CELLTYPE_INTEGER = 'i';
  const CELLTYPE_PICTURE = 'p';
  const FONTWEIGHT = 'fw';
  const FONTWEIGHT_BOLD = 'b';
  const FONTWEIGHT_NORMAL = 'n';
  const ALIGNMENT = 'align';
  const ALIGNMENT_H_GENERAL = 'hgeneral';
  const ALIGNMENT_H_LEFT = 'hleft';
  const ALIGNMENT_H_CENTER = 'hcenter';
  const ALIGNMENT_H_RIGHT = 'hright';
  const ALIGNMENT_H_FILL = 'hfill';
  const ALIGNMENT_H_JUSTIFY = 'hjustify';
  const ALIGNMENT_H_CENTER_CONT = 'hcentercont';

  protected $spreadsheet = null;
  protected $sheets = [];
  protected $activesheet = 0;
  protected $type = self::EXPORT_TYPE_XLS;
  protected $filename = "file";

  public function __construct($type = self::EXPORT_TYPE_XLS)
  {
    $this->type = $type;
  }

  public function getCellTitle($index)
	{
		$alphabet = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		if ($index < 26) return $alphabet[$index];
		return $alphabet[floor($index / 26)-1] . $alphabet[$index-(floor($index / 26)*26)];
	}

  public function getCell($row, $col)
  {
    return $this->getCellTitle($col) . (string)$row;
  }

  public function addSheet($name)
  {
    $this->sheets[$name] = [];
  }

  public function setSheet($oldname, $newname)
  {
    if (array_key_exists($oldname, $this->sheets) && !array_key_exists($newname, $this->sheets))
    {
      $this->sheets[$newname] = $this->sheets[$oldname];
      unset($this->sheets[$oldname]);
      return true;
    }
    else {
      return false;
    }
  }

  public function getCellValue($sheet, $row, $col)
  {
    if (array_key_exists($sheet, $this->sheets))
    {
      if (array_key_exists($this->getCell($row, $col), $this->sheets[$sheet]))
      {
        return $this->sheets[$this->getCell($row, $col)]
      }
    }
    return null;
  }

  public function setFilename($filename)
  {
    $this->filename = $filename;
  }

  abstract public function createSpreadsheet();
  abstract public function setCellValue($sheet, $row, $col, $data);
  abstract protected function setCellSpreadsheet($sheet, $cell);
  abstract protected function setSpreadsheetProperties($title = "", $subject = "", $description = "", $creator = "", $modificator = "");
  abstract protected function send();

  public function getSpreadsheet()
  {
    return $this->spreadsheet;
  }

  public function sendFile($title = "", $subject = "", $description = "", $creator = "", $modificator = "")
  {
    if (null == $this->spreadsheet) $this->createSpreadsheet();
    if (null != $this->spreadsheet)
    {
      foreach ($this->sheets as $sheet_title => $sheet)
      {
        foreach ($sheet as $cellpos => $celldata)
        {
          $this->setCellSpreadsheet($sheet_title, $celldata);
        }
      }
    }

    $this->setSpreadsheetProperties($title, $subject, $description, $creator, $modificator);
    $this->send();
  }
}
