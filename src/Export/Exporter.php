<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle\Export;

abstract class Exporter
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
    const BORDER_NONE = 'bn';
    const BORDER_DASHDOT = 'bdd';
    const BORDER_DASHDOTDOT = 'bddd';
    const BORDER_DASHED = 'bda';
    const BORDER_DOTTED = 'bdo';
    const BORDER_DOUBLE = 'bdou';
    const BORDER_HAIR = 'bh';
    const BORDER_MEDIUM = 'bm';
    const BORDER_MEDIUMDASHDOT = 'mdd';
    const BORDER_MEDIUMDASHDOTDOT = 'mddd';
    const BORDER_MEDIUMDASHED = 'md';
    const BORDER_SLANTDASHDOT = 'sdd';
    const BORDER_THICK = 'bt';
    const BORDER_THIN = 'bti';
    const TEXTROTATE = 'tr';
    const TEXTROTATE_CLOCKWISE = 'trc';
    const TEXTROTATE_COUNTERCLOCKWISE = 'trcc';
    const TEXTROTATE_NONE = 'tr0';

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
        if (false == $separator) {
            // single cell
            return [$this->getRowFromCell($range), $this->getColFromCell($range)];
        }

        // cell range
        $res = explode(':', $range);

        return [$this->getRowFromCell($res[0]), $this->getColFromCell($res[0]), $this->getRowFromCell($res[1]), $this->getColFromCell($res[1])];
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

    /**
     * Move the six metadata rows to a separate sheet, retaining question titles
     * there for orientation. The results sheet starts with its title row.
     */
    public function separateQuestionOverview($sheet, $overview, $titleLabel)
    {
        $this->addSheet($overview);
        $cells = $this->sheets[$sheet];
        $this->sheets[$sheet] = [];
        $shift = function ($cell, $rowOffset, $colOffset) {
            $cell[self::ROW] += $rowOffset;
            $cell[self::COL] += $colOffset;
            if (isset($cell[self::MERGE])) {
                $cell[self::MERGE] = preg_replace_callback('/[A-Z]+[0-9]+/', function ($match) use ($rowOffset, $colOffset) {
                    return $this->getCell($this->getRowFromCell($match[0]) + $rowOffset, $this->getColFromCell($match[0]) + $colOffset);
                }, $cell[self::MERGE]);
            }
            return $cell;
        };
        foreach ($cells as $cell) {
            if ($cell[self::ROW] < 6 || ($cell[self::ROW] === 6 && $cell[self::COL] >= 5)) {
                if ($cell[self::COL] >= 4) {
                    $overviewRow = $cell[self::ROW] === 6 ? 1 : ($cell[self::ROW] === 0 ? 0 : $cell[self::ROW] + 1);
                    $copy = $shift($cell, $overviewRow - $cell[self::ROW], -4);
                    unset($copy[self::BORDERBOTTOM], $copy[self::BORDERBOTTOMCOLOR]);
                    // Question ID, question number and response counts are integers.
                    if ($cell[self::COL] >= 5 && in_array($cell[self::ROW], [0, 1, 4, 5], true)) {
                        $copy[self::CELLTYPE] = self::CELLTYPE_INTEGER;
                    }
                    $copy[self::COLWIDTH] = 24 * 256;
                    $copy[self::TEXTWRAP] = true;
                    $this->sheets[$overview][$this->getCell($copy[self::ROW], $copy[self::COL])] = $copy;
                }
            }
            if ($cell[self::ROW] >= 6) {
                $copy = $shift($cell, -6, 0);
                $this->sheets[$sheet][$this->getCell($copy[self::ROW], $copy[self::COL])] = $copy;
            }
        }
        $this->setCellValue($overview, 1, 0, [
            self::DATA => $titleLabel.':',
            self::BGCOLOR => '#C0C0C0',
            self::COLOR => '#000000',
            self::ALIGNMENT => self::ALIGNMENT_H_RIGHT,
            self::FONTWEIGHT => self::FONTWEIGHT_BOLD,
            self::COLWIDTH => 24 * 256,
        ]);
    }

    /**
     * Drop an unused question subheading row and retain its separator.
     * Coordinates are zero-based, as in setCellValue().
     */
    public function omitEmptySubheadingRow($sheet, $row)
    {
        foreach ($this->sheets[$sheet] as $cell) {
            if ($cell[self::ROW] === $row && (string) ($cell[self::DATA] ?? '') !== '') {
                return;
            }
        }

        $cells = $this->sheets[$sheet];
        foreach ($cells as $cell) {
            if ($cell[self::ROW] !== $row) {
                continue;
            }
            $above = $this->getCell($row - 1, $cell[self::COL]);
            foreach ([self::BORDERBOTTOM, self::BORDERBOTTOMCOLOR] as $property) {
                if (isset($cell[$property], $cells[$above])) {
                    $cells[$above][$property] = $cell[$property];
                }
            }
        }

        $this->sheets[$sheet] = [];
        foreach ($cells as $cell) {
            if ($cell[self::ROW] === $row) {
                continue;
            }
            if ($cell[self::ROW] > $row) {
                --$cell[self::ROW];
            }
            if (isset($cell[self::MERGE])) {
                $cell[self::MERGE] = preg_replace_callback('/([A-Z]+)([0-9]+)/', static function ($match) use ($row) {
                    return $match[1].((int) $match[2] > $row + 1 ? (int) $match[2] - 1 : $match[2]);
                }, $cell[self::MERGE]);
            }
            $this->sheets[$sheet][$this->getCell($cell[self::ROW], $cell[self::COL])] = $cell;
        }
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
        if (null == $this->spreadsheet) {
            $this->createSpreadsheet();
        }
        if (null != $this->spreadsheet) {
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
        $pow = \strlen((string) $col) - 1;
        $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        for ($i = 0; $i < \strlen((string) $col); ++$i) {
            $index += pow(26, $pow) * (array_search(strtoupper($col[$i]), $alphabet, true) + 1);
            --$pow;
        }

        return $index - 1;
    }

    protected function getRowFromCell($cell)
    {
        $col = '';
        $row = '';
        for ($i = 0; $i < \strlen((string) $cell); ++$i) {
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
        for ($i = 0; $i < \strlen((string) $cell); ++$i) {
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
