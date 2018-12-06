<?php

namespace Hschottm\SurveyBundle;

class ExcelExporter
{
  private $spreadsheet = null;
  private $sheets = [];
  private $activesheet = 0;

  public function __construct($objSpreadsheet)
  {
    $this->spreadsheet = $objSpreadsheet;
  }

  public function getCellTitle($index)
	{
		$alphabet = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		if ($index < 26) return $alphabet[$index];
		return $alphabet[floor($index / 26)-1] . $alphabet[$index-(floor($index / 26)*26)];
	}

}
