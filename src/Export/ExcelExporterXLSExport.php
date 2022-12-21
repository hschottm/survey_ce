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

use Contao\StringUtil;
use Hschottm\ExcelXLSBundle\xlsexport;

class ExcelExporterXLSExport extends Exporter
{
    public function __construct($type = self::EXPORT_TYPE_XLS)
    {
        parent::__construct($type);
    }

    public function createSpreadsheet(): void
    {
        $this->spreadsheet = new xlsexport();
    }

    public function setCellValue($sheet, $row, $col, $data)
    {
        if (\array_key_exists($sheet, $this->sheets)) {
            $celldata = [
                self::ROW => $row,
                self::COL => $col,
            ];

            foreach ($data as $key => $value) {
                $celldata[$key] = $value;
            }

            if (!\array_key_exists(self::CELLTYPE, $celldata)) {
                $celldata[self::CELLTYPE] = self::CELLTYPE_STRING;
            }

            if (self::CELLTYPE_STRING === $celldata[self::CELLTYPE]) {
                $celldata[self::DATA] = utf8_decode($celldata[self::DATA]);
            }
            $this->sheets[$sheet][$this->getCell($row, $col)] = $celldata;

            return true;
        }

        return false;
    }

    protected function setSpreadsheetProperties($title = '', $subject = '', $description = '', $creator = '', $modificator = ''): void
    {
        /*
        $this->spreadsheet->getProperties()->setCreator($creator);
        $this->spreadsheet->getProperties()->setLastModifiedBy($creator);
        $this->spreadsheet->getProperties()->setTitle($title);
        $this->spreadsheet->getProperties()->setSubject($subject);
        $this->spreadsheet->getProperties()->setDescription($description);
        */
    }

    protected function send(): void
    {
        $this->spreadsheet->sendFile(StringUtil::sanitizeFileName(htmlspecialchars_decode($this->filename)).'.xls');
        exit;
    }

    protected function setCellSpreadsheet($sheet, $cell): void
    {
        $pos = $this->getCell($cell[self::ROW] + 1, $cell[self::COL]);
        $found = false;

        foreach ($this->spreadsheet->worksheets as $sheetarray) {
            if ($sheetarray['sheetname'] === utf8_decode($sheet)) {
                $found = true;
            }
        }

        if (!$found) {
            $this->spreadsheet->addworksheet(utf8_decode($sheet));
        }
        $data = [
            'sheetname' => utf8_decode($sheet),
            'row' => $cell[self::ROW],
            'col' => $cell[self::COL],
            'data' => $cell[self::DATA],
        ];
        $this->spreadsheet->setcell($data);

        switch ($cell[self::CELLTYPE]) {
      case self::CELLTYPE_STRING:
        $data['type'] = CELL_STRING;
        break;

      case self::CELLTYPE_FLOAT:
        $data['type'] = CELL_FLOAT;
        break;

      case self::CELLTYPE_PICTURE:
        $data['type'] = CELL_PICTURE;
        break;

      case self::CELLTYPE_INTEGER:
      default:
        break;
    }

        if (\array_key_exists(self::BGCOLOR, $cell)) {
            $data['bgcolor'] = $cell[self::BGCOLOR];
        }

        if (\array_key_exists(self::COLOR, $cell)) {
            $data['color'] = $cell[self::COLOR];
        }

        if (\array_key_exists(self::ALIGNMENT, $cell)) {
            switch ($cell[self::ALIGNMENT]) {
        case self::ALIGNMENT_H_GENERAL:
            $data['hallign'] = XLSXF_HALLIGN_GENERAL;
            break;

          case self::ALIGNMENT_H_LEFT:
            $data['hallign'] = XLSXF_HALLIGN_LEFT;
            break;

          case self::ALIGNMENT_H_CENTER:
            $data['hallign'] = XLSXF_HALLIGN_CENTER;
            break;

          case self::ALIGNMENT_H_RIGHT:
            $data['hallign'] = XLSXF_HALLIGN_RIGHT;
            break;

          case self::ALIGNMENT_H_FILL:
            $data['hallign'] = XLSXF_HALLIGN_FILL;
            break;

          case self::ALIGNMENT_H_JUSTIFY:
            $data['hallign'] = XLSXF_HALLIGN_JUSTIFY;
            break;

          case self::ALIGNMENT_H_CENTER_CONT:
            $data['hallign'] = XLSXF_HALLIGN_CACROSS;
            break;
      }
        }

        if (\array_key_exists(self::TEXTROTATE, $cell)) {
            switch ($cell[self::TEXTROTATE]) {
        case self::TEXTROTATE_CLOCKWISE:
          $data['textrotate'] = XLSXF_TEXTROTATION_CLOCKWISE;
          break;

        case self::TEXTROTATE_COUNTERCLOCKWISE:
          $data['textrotate'] = XLSXF_TEXTROTATION_COUNTERCLOCKWISE;
          break;

        case self::TEXTROTATE_NONE:
          break;
      }
        }

        if (\array_key_exists(self::TEXTWRAP, $cell)) {
            if ($cell[self::TEXTWRAP]) {
                $data['textwrap'] = '1';
            }
        }

        if (\array_key_exists(self::COLWIDTH, $cell)) {
            $data['colwidth'] = $cell[self::COLWIDTH];
        }

        if (\array_key_exists(self::FONTWEIGHT, $cell)) {
            switch ($cell[self::FONTWEIGHT]) {
        case self::FONTWEIGHT_BOLD:
          $data['fontweight'] = XLSFONT_BOLD;
          break;
      }
        }

        if (\array_key_exists(self::BORDERBOTTOM, $cell)) {
            switch ($cell[self::BORDERBOTTOM]) {
        case self::BORDER_THIN:
          $data['borderbottom'] = XLSXF_BORDER_THIN;
          break;

        case self::BORDER_HAIR:
          $data['borderbottom'] = XLSXF_BORDER_HAIR;
          break;

        case self::BORDER_THICK:
          $data['borderbottom'] = XLSXF_BORDER_THICK;
          break;

        case self::BORDER_DOTTED:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DOUBLE:
          $data['borderbottom'] = XLSXF_BORDER_DOUBLE;
          break;

        case self::BORDER_MEDIUM:
          $data['borderbottom'] = XLSXF_BORDER_MEDIUM;
          break;

        case self::BORDER_DASHED:
          $data['borderbottom'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_DASHDOT:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DASHDOTDOT:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHED:
          $data['borderbottom'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_SLANTDASHDOT:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $data['borderbottom'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_NONE:
          $data['borderbottom'] = XLSXF_BORDER_NOBORDER;
          break;
      }
        }

        if (\array_key_exists(self::BORDERBOTTOMCOLOR, $cell)) {
            $data['borderbottomcolor'] = $cell[self::BORDERBOTTOMCOLOR];
        }

        if (\array_key_exists(self::BORDERTOP, $cell)) {
            switch ($cell[self::BORDERTOP]) {
        case self::BORDER_THIN:
          $data['bordertop'] = XLSXF_BORDER_THIN;
          break;

        case self::BORDER_HAIR:
          $data['bordertop'] = XLSXF_BORDER_HAIR;
          break;

        case self::BORDER_THICK:
          $data['bordertop'] = XLSXF_BORDER_THICK;
          break;

        case self::BORDER_DOTTED:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DOUBLE:
          $data['bordertop'] = XLSXF_BORDER_DOUBLE;
          break;

        case self::BORDER_MEDIUM:
          $data['bordertop'] = XLSXF_BORDER_MEDIUM;
          break;

        case self::BORDER_DASHED:
          $data['bordertop'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_DASHDOT:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DASHDOTDOT:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHED:
          $data['bordertop'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_SLANTDASHDOT:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $data['bordertop'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_NONE:
          $data['bordertop'] = XLSXF_BORDER_NOBORDER;
          break;
      }
        }

        if (\array_key_exists(self::BORDERTOPCOLOR, $cell)) {
            $data['bordertopcolor'] = $cell[self::BORDERTOPCOLOR];
        }

        if (\array_key_exists(self::BORDERLEFT, $cell)) {
            switch ($cell[self::BORDERLEFT]) {
        case self::BORDER_THIN:
          $data['borderleft'] = XLSXF_BORDER_THIN;
          break;

        case self::BORDER_HAIR:
          $data['borderleft'] = XLSXF_BORDER_HAIR;
          break;

        case self::BORDER_THICK:
          $data['borderleft'] = XLSXF_BORDER_THICK;
          break;

        case self::BORDER_DOTTED:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DOUBLE:
          $data['borderleft'] = XLSXF_BORDER_DOUBLE;
          break;

        case self::BORDER_MEDIUM:
          $data['borderleft'] = XLSXF_BORDER_MEDIUM;
          break;

        case self::BORDER_DASHED:
          $data['borderleft'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_DASHDOT:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DASHDOTDOT:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHED:
          $data['borderleft'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_SLANTDASHDOT:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $data['borderleft'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_NONE:
          $data['borderleft'] = XLSXF_BORDER_NOBORDER;
          break;
      }
        }

        if (\array_key_exists(self::BORDERLEFTCOLOR, $cell)) {
            $data['borderleftcolor'] = $cell[self::BORDERLEFTCOLOR];
        }

        if (\array_key_exists(self::BORDERRIGHT, $cell)) {
            switch ($cell[self::BORDERRIGHT]) {
        case self::BORDER_THIN:
          $data['borderright'] = XLSXF_BORDER_THIN;
          break;

        case self::BORDER_HAIR:
          $data['borderright'] = XLSXF_BORDER_HAIR;
          break;

        case self::BORDER_THICK:
          $data['borderright'] = XLSXF_BORDER_THICK;
          break;

        case self::BORDER_DOTTED:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DOUBLE:
          $data['borderright'] = XLSXF_BORDER_DOUBLE;
          break;

        case self::BORDER_MEDIUM:
          $data['borderright'] = XLSXF_BORDER_MEDIUM;
          break;

        case self::BORDER_DASHED:
          $data['borderright'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_DASHDOT:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_DASHDOTDOT:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHED:
          $data['borderright'] = XLSXF_BORDER_DASHED;
          break;

        case self::BORDER_SLANTDASHDOT:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $data['borderright'] = XLSXF_BORDER_DOTTED;
          break;

        case self::BORDER_NONE:
          $data['borderright'] = XLSXF_BORDER_NOBORDER;
          break;
      }
        }

        if (\array_key_exists(self::BORDERRIGHTCOLOR, $cell)) {
            $data['borderrightcolor'] = $cell[self::BORDERRIGHTCOLOR];
        }

        if (\array_key_exists(self::MERGE, $cell)) {
            $range = $cell[self::MERGE];
            $rangeArray = $this->getArrayFromRange($range);
            $this->spreadsheet->merge_cells($sheet, $rangeArray[0], $rangeArray[2], $rangeArray[1], $rangeArray[3]);
        }

        if (\array_key_exists(self::FONTSTYLE, $cell)) {
            switch ($cell[self::FONTSTYLE]) {
        case self::FONTSTYLE_ITALIC:
          $data['fontstyle'] = XLSFONT_STYLE_ITALIC;
          break;
      }
        }
        $this->spreadsheet->setcell($data);
    }
}
