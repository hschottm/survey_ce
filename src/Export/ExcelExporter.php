<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle\Export;

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
    const FONTSTYLE = 'fs';
    const FONTSTYLE_ITALIC = 'i';
    const ALIGNMENT = 'align';
    const ALIGNMENT_H_GENERAL = 'hgeneral';
    const ALIGNMENT_H_LEFT = 'hleft';
    const ALIGNMENT_H_CENTER = 'hcenter';
    const ALIGNMENT_H_RIGHT = 'hright';
    const ALIGNMENT_H_FILL = 'hfill';
    const ALIGNMENT_H_JUSTIFY = 'hjustify';
    const ALIGNMENT_H_CENTER_CONT = 'hcentercont';
    const TEXTWRAP = 'tw';
    const COLWIDTH = 'cw';
    const COLWIDTH_AUTO = 'a';
    const MERGE = 'merge';
    const BORDERBOTTOM = 'bb';
    const BORDERBOTTOMCOLOR = 'bbc';
    const BORDERTOP = 'bt';
    const BORDERTOPCOLOR = 'btc';
    const BORDERLEFT = 'bl';
    const BORDERLEFTCOLOR = 'blc';
    const BORDERRIGHT = 'br';
    const BORDERRIGHTCOLOR = 'brc';
    const BORDER_NONE	= 'bn';
    const BORDER_DASHDOT	= 'bdd';
    const BORDER_DASHDOTDOT	 = 'bddd';
    const BORDER_DASHED	= 'bda';
    const BORDER_DOTTED	= 'bdo';
    const BORDER_DOUBLE	= 'bdou';
    const BORDER_HAIR	= 'bh';
    const BORDER_MEDIUM	= 'bm';
    const BORDER_MEDIUMDASHDOT = 'mdd';
    const BORDER_MEDIUMDASHDOTDOT	= 'mddd';
    const BORDER_MEDIUMDASHED	= 'md';
    const BORDER_SLANTDASHDOT	= 'sdd';
    const BORDER_THICK = 'bt';
    const BORDER_THIN = 'bti';
    const TEXTROTATE = 'tr';
    const TEXTROTATE_CLOCKWISE = 'trc';
    const TEXTROTATE_COUNTERCLOCKWISE = 'trcc';

    protected $spreadsheet;
    protected $sheets = [];
    protected $activesheet = 0;
    protected $type = self::EXPORT_TYPE_XLS;
    protected $filename = 'file';

    public function __construct($type = self::EXPORT_TYPE_XLS)
    {
        $this->type = $type;
    }

    public function getColumnIndex($index)
    {
        $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        if ($index < 26) {
            return $alphabet[$index];
        }

        return $alphabet[floor($index / 26) - 1].$alphabet[$index - (floor($index / 26) * 26)];
    }

    protected function columnToIndex($col)
    {
      $index = 0;
      $pow = strlen($col)-1;
      $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
      for ($i = 0; $i <= strlen($col); $i++)
      {
          $index += pow(26, $pow) * (array_search(strtoupper($col[$i]) + 1);
          $pow--;
      }
      return $index-1;
    }

    public function getCell($row, $col)
    {
        return $this->getColumnIndex($col).(string) $row;
    }

    protected function getRowFromCell($cell)
    {
      $col = '';
      $row = '';
      for ($i = 0; $i < strlen($cell); $i++)
      {
        $char = $cell[$i];
        if (ctype_alpha($char))
        {
          $col .= $char;
        }
        else {
          $row .= $char;
        }
      }
      return (int)$row - 1;
    }

    protected function getColFromCell($cell)
    {
      $col = '';
      $row = '';
      for ($i = 0; $i < strlen($cell); $i++)
      {
        $char = $cell[$i];
        if (ctype_alpha($char))
        {
          $col .= $char;
        }
        else {
          $row .= $char;
        }
      }
      return $this->columnToIndex($col);
    }

    public function getArrayFromRange($range)
    {
      $separator = strpos($range, ':');
      if ($separator === false)
      {
        // single cell
        return array($this->getRowFromCell($range), $this->getColFromCell($range));
      }
      else {
        // cell range
        $res = explode(':', $range);
        return array($this->getRowFromCell($res[0]), $this->getColFromCell($res[0]), $this->getRowFromCell($res[1]), $this->getColFromCell($res[1]));
      }
    }

    public function addSheet($name)
    {
        $this->sheets[$name] = [];
    }

    public function setSheet($oldname, $newname)
    {
        if (array_key_exists($oldname, $this->sheets) && !array_key_exists($newname, $this->sheets)) {
            $this->sheets[$newname] = $this->sheets[$oldname];
            unset($this->sheets[$oldname]);

            return true;
        }

        return false;
    }

    public function getCellValue($sheet, $row, $col)
    {
        if (array_key_exists($sheet, $this->sheets)) {
            if (array_key_exists($this->getCell($row, $col), $this->sheets[$sheet])) {
                return $this->sheets[$this->getCell($row, $col)];
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

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    public function sendFile($title = '', $subject = '', $description = '', $creator = '', $modificator = '')
    {
        if (null === $this->spreadsheet) {
            $this->createSpreadsheet();
        }
        if (null !== $this->spreadsheet) {
            foreach ($this->sheets as $sheet_title => $sheet) {
                foreach ($sheet as $cellpos => $celldata) {
                    $this->setCellSpreadsheet($sheet_title, $celldata);
                }
            }
        }

        $this->setSpreadsheetProperties($title, $subject, $description, $creator, $modificator);
        $this->send();
    }

    abstract protected function setCellSpreadsheet($sheet, $cell);

    abstract protected function setSpreadsheetProperties($title = '', $subject = '', $description = '', $creator = '', $modificator = '');

    abstract protected function send();
}
