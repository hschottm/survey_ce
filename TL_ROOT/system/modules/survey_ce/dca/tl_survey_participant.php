<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Table tl_survey_participant
 */
$GLOBALS['TL_DCA']['tl_survey_participant'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_survey',
		'doNotCopyRecords'            => true,
		'enableVersioning'            => true,
		'closed'                      => true,
		'ondelete_callback' => array
		(
			array('tl_survey_participant','deleteParticipant')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('lastpage', 'tstamp'),
			'flag'                    => 11, // sort ASC ungrouped  on initial display
			'panelLayout'             => 'sort,filter,limit'
		),
		'label' => array
		(
			'fields'                  => array('pin','uid','finished'),
			'label_callback'          => array('tl_survey_participant', 'getLabel')
		),
		'global_operations' => array
		(
			'exportraw' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_participant']['exportraw'],
				'href'                => 'key=exportraw',
				'class'               => 'header_export',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'deleteall' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['deleteAll'],
				'href'                => 'act=deleteAll',
				'class'               => 'header_delete_all',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['delAllConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_participant']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_participant']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_participant']['tstamp'],
			'sorting'                 => true,
			'flag'                    => 5, // sort ASC grouped by day
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>16, 'rgxp'=>'datim', 'insertTag'=>true),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'uid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pin' => array
		(
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'lastpage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_participant']['lastpage'],
			'sorting'                 => true,
			'flag'                    => 3, // sort ASC grouped by first X chars
			'length'                  => 2, // group by first 2 chars
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>16, 'rgxp'=>'digit'),
			'sql'                     => "int(10) unsigned NOT NULL default '1'"
		),
		'finished' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'email' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'firstname' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lastname' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'company' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
	)
);

/**
 * Class tl_survey_participant
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_survey_participant extends Backend
{
	protected $pageCount = null;
	
	public function deleteParticipant($dc)
	{
		$objResult = $this->Database->prepare("SELECT * FROM " . $dc->table . " WHERE (id=?)")
			->execute($dc->id);
		if ($objResult->next())
		{
			setcookie('TLsvy_' . $objResult->pid, $objResult->pin, time()-3600, "/");
			$objDelete = $this->Database->prepare("DELETE FROM tl_survey_pin_tan WHERE (pid=? AND pin=?)")
				->execute($objResult->pid, $objResult->pin);
			$objDelete = $this->Database->prepare("DELETE FROM tl_survey_result WHERE (pid=? AND pin=?)")
				->execute($objResult->pid, $objResult->pin);
		}
	}
	
	public function getUsername($uid)
	{
		$data = $this->Database->prepare("SELECT * FROM tl_member WHERE (id=?)")
			->execute($uid)
			->fetchAssoc();
		return trim($data["firstname"] . " " . $data["lastname"]);
	}
	
	public function getLabel($row, $label)
	{
		// we ignore the label param, the row has it all
		$finished = intval($row['finished']);
		$result = sprintf(
			'<div>%s, <strong>%s</strong> <span style="color: #7f7f7f;">[%s%s]</span></div>',
			date($GLOBALS['TL_CONFIG']['datimFormat'], $row['tstamp']),
			($row['uid'] > 0)
				? $this->getUsername($row['uid'])
				: $row['pin'],
			($finished)
				? $GLOBALS['TL_LANG']['tl_survey_participant']['finished']
				: $GLOBALS['TL_LANG']['tl_survey_participant']['running'],
			($finished)
				? ''
				: ' (' . $row['lastpage'] . '/' . $this->getPageCount($row['pid']) . ')'
		);
		return $result;
	}

	/**
	 * Returns the surveys number of pages (cached).
	 * @param int
	 * @return int
	 */
	protected function getPageCount($survey_id) 
	{
		if (!isset($this->pageCount))
		{
			$objCount = $this->Database->prepare("
					SELECT COUNT(*) AS pagecount
					FROM   tl_survey_page
					WHERE  pid=?
				")->execute($survey_id);
			$this->pageCount = $objCount->pagecount;
		}
		return $this->pageCount;
	}
}

?>