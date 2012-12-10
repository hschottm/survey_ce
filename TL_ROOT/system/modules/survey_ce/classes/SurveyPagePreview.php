<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class SurveyPagePreview
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyPagePreview extends Backend
{
	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	public function compilePreview($row, $blnWriteToFile=false)
	{
		$objElements = $this->Database->prepare("SELECT * FROM tl_survey_page WHERE (pid=? AND sorting < ?)")
			->execute($row["pid"], $row["sorting"]);
		$position = $objElements->numRows + 1;

		$template = new FrontendTemplate('be_survey_page_preview');
		$template->page = $GLOBALS['TL_LANG']['tl_survey_page']['page'];
		$template->position = $position;
		$template->title = specialchars($row['title']);
		$template->description = specialchars($row['description']);
		return $template->parse();
	}

}

?>