<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle\Export;

use Sonata\Exporter\Writer\CsvWriter;
use Contao\StringUtil;

class CSVExporter extends Exporter
{
    private $tempName;
    private $data = [];
    private $header = array();
    private $rows = array();

    public function __construct($type = self::EXPORT_TYPE_XLS)
    {
        parent::__construct($type);
        $this->tempName = tempnam(sys_get_temp_dir(), 'CSV_EXPORT');
        unlink($this->tempName);
    }

    public function createSpreadsheet()
    {
        $this->spreadsheet = new CsvWriter($this->tempName, ',', '"', '\\', false);
    }

    public function setCellValue($sheet, $row, $col, $data)
    {
        if (array_key_exists($sheet, $this->sheets)) {
            $celldata = [
        self::ROW => $row,
        self::COL => $col,
      ];
            foreach ($data as $key => $value) {
                $celldata[$key] = $value;
            }
            if (!array_key_exists(self::CELLTYPE, $celldata)) {
                $celldata[self::CELLTYPE] = self::CELLTYPE_STRING;
            }
            if (self::CELLTYPE_STRING == $celldata[self::CELLTYPE]) {
                $celldata[self::DATA] = utf8_decode($celldata[self::DATA]);
            }
            /*
            if ($row == 0) {
                $this->header[$col] = $celldata['data'];
            } else {
                if (!array_key_exists($row, $this->rows)) {
                    $this->rows[$row] = array();
                }
                $this->rows[$row][$col] = $celldata['data'];
            }
                */
            $this->sheets[$sheet][$this->getCell($row, $col)] = $celldata;
            return true;
        }

        return false;
    }

    protected function setSpreadsheetProperties($title = '', $subject = '', $description = '', $creator = '', $modificator = '')
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
        foreach ($this->data as $row) {
            $this->spreadsheet->write($row);
        }
        $this->spreadsheet->close();
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.StringUtil::sanitizeFileName(htmlspecialchars_decode($this->filename)).'.csv'.'"');
        readfile($this->tempName);
        unlink($this->tempName);
        exit;
    }

    protected function setCellSpreadsheet($sheet, $cell)
    {
        $this->data[$cell[self::ROW]][$cell[self::COL]] = $cell[self::DATA];
    }
}
