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

namespace Hschottm\SurveyBundle\Export;

abstract class Exporter
{
    public const EXPORT_TYPE_XLS = 'xls';
    public const EXPORT_TYPE_XLSX = 'xlsx';

    public const ROW = 'r';
    public const COL = 'c';
    public const DATA = 'data';
    public const COLOR = 'col';
    public const BGCOLOR = 'bgcol';
    public const CELLTYPE = 'type';
    public const CELLTYPE_STRING = 's';
    public const CELLTYPE_FLOAT = 'f';
    public const CELLTYPE_INTEGER = 'i';
    public const CELLTYPE_PICTURE = 'p';
    public const FONTWEIGHT = 'fw';
    public const FONTWEIGHT_BOLD = 'b';
    public const FONTWEIGHT_NORMAL = 'n';
    public const FONTSTYLE = 'fs';
    public const FONTSTYLE_ITALIC = 'i';
    public const ALIGNMENT = 'align';
    public const ALIGNMENT_H_GENERAL = 'hgeneral';
    public const ALIGNMENT_H_LEFT = 'hleft';
    public const ALIGNMENT_H_CENTER = 'hcenter';
    public const ALIGNMENT_H_RIGHT = 'hright';
    public const ALIGNMENT_H_FILL = 'hfill';
    public const ALIGNMENT_H_JUSTIFY = 'hjustify';
    public const ALIGNMENT_H_CENTER_CONT = 'hcentercont';
    public const TEXTWRAP = 'tw';
    public const COLWIDTH = 'cw';
    public const COLWIDTH_AUTO = 'a';
    public const MERGE = 'merge';
    public const BORDERBOTTOM = 'bb';
    public const BORDERBOTTOMCOLOR = 'bbc';
    public const BORDERTOP = 'bt';
    public const BORDERTOPCOLOR = 'btc';
    public const BORDERLEFT = 'bl';
    public const BORDERLEFTCOLOR = 'blc';
    public const BORDERRIGHT = 'br';
    public const BORDERRIGHTCOLOR = 'brc';
    public const BORDER_NONE = 'bn';
    public const BORDER_DASHDOT = 'bdd';
    public const BORDER_DASHDOTDOT = 'bddd';
    public const BORDER_DASHED = 'bda';
    public const BORDER_DOTTED = 'bdo';
    public const BORDER_DOUBLE = 'bdou';
    public const BORDER_HAIR = 'bh';
    public const BORDER_MEDIUM = 'bm';
    public const BORDER_MEDIUMDASHDOT = 'mdd';
    public const BORDER_MEDIUMDASHDOTDOT = 'mddd';
    public const BORDER_MEDIUMDASHED = 'md';
    public const BORDER_SLANTDASHDOT = 'sdd';
    public const BORDER_THICK = 'bt';
    public const BORDER_THIN = 'bti';
    public const TEXTROTATE = 'tr';
    public const TEXTROTATE_CLOCKWISE = 'trc';
    public const TEXTROTATE_COUNTERCLOCKWISE = 'trcc';
    public const TEXTROTATE_NONE = 'tr0';

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

    public function getCell($row, $col)
    {
        return $this->getColumnIndex($col).(string) ($row + 1);
    }

    public function getArrayFromRange($range)
    {
        $separator = strpos($range, ':');

        if (false === $separator) {
            // single cell
            return [$this->getRowFromCell($range), $this->getColFromCell($range)];
        }

        // cell range
        $res = explode(':', $range);

        return [$this->getRowFromCell($res[0]), $this->getColFromCell($res[0]), $this->getRowFromCell($res[1]), $this->getColFromCell($res[1])];
    }

    public function addSheet($name): void
    {
        $this->sheets[$name] = [];
    }

    public function setSheet($oldname, $newname)
    {
        if (\array_key_exists($oldname, $this->sheets) && !\array_key_exists($newname, $this->sheets)) {
            $this->sheets[$newname] = $this->sheets[$oldname];
            unset($this->sheets[$oldname]);

            return true;
        }

        return false;
    }

    public function getCellValue($sheet, $row, $col)
    {
        if (\array_key_exists($sheet, $this->sheets)) {
            if (\array_key_exists($this->getCell($row, $col), $this->sheets[$sheet])) {
                return $this->sheets[$this->getCell($row, $col)];
            }
        }

        return null;
    }

    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    abstract public function createSpreadsheet();

    abstract public function setCellValue($sheet, $row, $col, $data);

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    public function sendFile($title = '', $subject = '', $description = '', $creator = '', $modificator = ''): void
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

    protected function columnToIndex($col)
    {
        $index = 0;
        $pow = \strlen($col) - 1;
        $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        for ($i = 0; $i < \strlen($col); ++$i) {
            $index += pow(26, $pow) * (array_search(strtoupper($col[$i]), $alphabet, true) + 1);
            --$pow;
        }

        return $index - 1;
    }

    protected function getRowFromCell($cell)
    {
        $col = '';
        $row = '';

        for ($i = 0; $i < \strlen($cell); ++$i) {
            $char = $cell[$i];

            if (ctype_alpha($char)) {
                $col .= $char;
            } else {
                $row .= $char;
            }
        }

        return (int) $row - 1;
    }

    protected function getColFromCell($cell)
    {
        $col = '';
        $row = '';

        for ($i = 0; $i < \strlen($cell); ++$i) {
            $char = $cell[$i];

            if (ctype_alpha($char)) {
                $col .= $char;
            } else {
                $row .= $char;
            }
        }

        return $this->columnToIndex($col);
    }

    abstract protected function setCellSpreadsheet($sheet, $cell);

    abstract protected function setSpreadsheetProperties($title = '', $subject = '', $description = '', $creator = '', $modificator = '');

    abstract protected function send();
}
