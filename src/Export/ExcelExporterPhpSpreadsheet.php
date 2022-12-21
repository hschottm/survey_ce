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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExporterPhpSpreadsheet extends Exporter
{
    public function __construct($type = self::EXPORT_TYPE_XLS)
    {
        parent::__construct($type);
    }

    public function webColorToARGB($color)
    {
        $col = str_replace('#', '', $color);

        if (6 === \strlen($col)) {
            return 'ff'.$col;
        }

        if (8 === \strlen($color)) {
            return $col;
        }

        return 'ff000000';
    }

    public function createSpreadsheet(): void
    {
        /*
        if ($this->type == self::EXPORT_TYPE_XLS)
        {
          $this->spreadsheet = new xlsexport();
        }
        */
        $this->spreadsheet = new Spreadsheet();

        if ($this->spreadsheet->getSheetCount() > 0) {
            $this->spreadsheet->removeSheetByIndex(0);
        }
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
            $this->sheets[$sheet][$this->getCell($row, $col)] = $celldata;

            return true;
        }

        return false;
    }

    protected function setSpreadsheetProperties($title = '', $subject = '', $description = '', $creator = '', $modificator = ''): void
    {
        $this->spreadsheet->getProperties()->setCreator($creator);
        $this->spreadsheet->getProperties()->setLastModifiedBy($creator);
        $this->spreadsheet->getProperties()->setTitle($title);
        $this->spreadsheet->getProperties()->setSubject($subject);
        $this->spreadsheet->getProperties()->setDescription($description);
    }

    protected function send(): void
    {
        $objWriter = new Xlsx($this->spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.StringUtil::sanitizeFileName(htmlspecialchars_decode($this->filename)).'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        echo '';
        exit;
    }

    protected function setCellSpreadsheet($sheet, $cell): void
    {
        $pos = $this->getCell($cell[self::ROW] + 1, $cell[self::COL]);
        $worksheet = $this->spreadsheet->getSheetByName($sheet);

        if (null === $worksheet) {
            $worksheet = $this->spreadsheet->addSheet(new Worksheet($this->spreadsheet, $sheet));
        }

        $worksheet->setCellValue($pos, $cell[self::DATA]);

        $fill_array = [];
        $font_array = [];

        switch ($cell[self::CELLTYPE]) {
      case self::CELLTYPE_STRING:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        break;

      case self::CELLTYPE_FLOAT:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        break;

      case self::CELLTYPE_PICTURE:
        break;

      case self::CELLTYPE_INTEGER:
      default:
        $worksheet->getStyle($pos)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
        break;
    }

        if (\array_key_exists(self::BGCOLOR, $cell)) {
            $worksheet->getStyle($pos)->getFill()->setFillType(Fill::FILL_SOLID);
            $worksheet->getStyle($pos)->getFill()->getStartColor()->setARGB(str_replace('#', 'FF', $cell[self::BGCOLOR]));
        }

        if (\array_key_exists(self::COLOR, $cell)) {
            $worksheet->getStyle($pos)->getFont()->getColor()->setARGB(str_replace('#', 'FF', $cell[self::COLOR]));
        }

        if (\array_key_exists(self::ALIGNMENT, $cell)) {
            switch ($cell[self::ALIGNMENT]) {
        case self::ALIGNMENT_H_GENERAL:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_GENERAL);
            break;

          case self::ALIGNMENT_H_LEFT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            break;

          case self::ALIGNMENT_H_CENTER:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            break;

          case self::ALIGNMENT_H_RIGHT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            break;

          case self::ALIGNMENT_H_FILL:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_FILL);
            break;

          case self::ALIGNMENT_H_JUSTIFY:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
            break;

          case self::ALIGNMENT_H_CENTER_CONT:
            $worksheet->getStyle($pos)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER_CONTINUOUS);
            break;
      }
        }

        if (\array_key_exists(self::TEXTROTATE, $cell)) {
            switch ($cell[self::TEXTROTATE]) {
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

        if (\array_key_exists(self::TEXTWRAP, $cell)) {
            $worksheet->getStyle($pos)->getAlignment()->setWrapText(true);
        }

        if (\array_key_exists(self::FONTWEIGHT, $cell)) {
            switch ($cell[self::FONTWEIGHT]) {
        case self::FONTWEIGHT_BOLD:
          $worksheet->getStyle($pos)->getFont()->setBold(true);
          break;
      }
        }

        if (\array_key_exists(self::BORDERBOTTOM, $cell)) {
            switch ($cell[self::BORDERBOTTOM]) {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
          break;

        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR);
          break;

        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
          break;

        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
          break;

        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
          break;

        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
          break;

        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED);
          break;

        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHDOT);
          break;

        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHDOTDOT);
          break;

        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUMDASHED);
          break;

        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_SLANTDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUMDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUMDASHDOTDOT);
          break;

        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_NONE);
          break;
      }
        }

        if (\array_key_exists(self::BORDERBOTTOMCOLOR, $cell)) {
            $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new Color($this->webColorToARGB($cell[self::BORDERBOTTOMCOLOR])));
        }

        if (\array_key_exists(self::BORDERTOP, $cell)) {
            switch ($cell[self::BORDERTOP]) {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
          break;

        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_HAIR);
          break;

        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THICK);
          break;

        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOTTED);
          break;

        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOUBLE);
          break;

        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
          break;

        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DASHED);
          break;

        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DASHDOT);
          break;

        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_DASHDOTDOT);
          break;

        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUMDASHED);
          break;

        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_SLANTDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUMDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUMDASHDOTDOT);
          break;

        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
          break;
      }
        }

        if (\array_key_exists(self::BORDERTOPCOLOR, $cell)) {
            $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new Color($this->webColorToARGB($cell[self::BORDERTOPCOLOR])));
        }

        if (\array_key_exists(self::BORDERLEFT, $cell)) {
            switch ($cell[self::BORDERLEFT]) {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
          break;

        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_HAIR);
          break;

        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THICK);
          break;

        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DOTTED);
          break;

        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DOUBLE);
          break;

        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
          break;

        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED);
          break;

        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHDOT);
          break;

        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHDOTDOT);
          break;

        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUMDASHED);
          break;

        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_SLANTDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUMDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUMDASHDOTDOT);
          break;

        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_NONE);
          break;
      }
        }

        if (\array_key_exists(self::BORDERLEFTCOLOR, $cell)) {
            $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new Color($this->webColorToARGB($cell[self::BORDERLEFTCOLOR])));
        }

        if (\array_key_exists(self::BORDERRIGHT, $cell)) {
            switch ($cell[self::BORDERRIGHT]) {
        case self::BORDER_THIN:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
          break;

        case self::BORDER_HAIR:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_HAIR);
          break;

        case self::BORDER_THICK:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THICK);
          break;

        case self::BORDER_DOTTED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DOTTED);
          break;

        case self::BORDER_DOUBLE:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DOUBLE);
          break;

        case self::BORDER_MEDIUM:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
          break;

        case self::BORDER_DASHED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DASHED);
          break;

        case self::BORDER_DASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DASHDOT);
          break;

        case self::BORDER_DASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DASHDOTDOT);
          break;

        case self::BORDER_MEDIUMDASHED:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUMDASHED);
          break;

        case self::BORDER_SLANTDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_SLANTDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUMDASHDOT);
          break;

        case self::BORDER_MEDIUMDASHDOTDOT:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUMDASHDOTDOT);
          break;

        case self::BORDER_NONE:
          $worksheet->getStyle($pos)->getBorders()->getRight()->setBorderStyle(Border::BORDER_NONE);
          break;
      }
        }

        if (\array_key_exists(self::BORDERRIGHTCOLOR, $cell)) {
            $worksheet->getStyle($pos)->getBorders()->getBottom()->setColor(new Color($this->webColorToARGB($cell[self::BORDERRIGHTCOLOR])));
        }

        if (\array_key_exists(self::MERGE, $cell)) {
            $worksheet->mergeCells($cell[self::MERGE]);
        }

        if (\array_key_exists(self::COLWIDTH, $cell)) {
            if (self::COLWIDTH_AUTO === $cell[self::COLWIDTH]) {
                $worksheet->getColumnDimension($this->getColumnIndex($cell[self::COL]))->setAutoSize(true);
            } else {
                $worksheet->getColumnDimension($this->getColumnIndex($cell[self::COL]))->setWidth($cell[self::COLWIDTH]);
            }
        }
    }
}
