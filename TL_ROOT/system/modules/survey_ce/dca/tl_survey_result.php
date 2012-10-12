<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Table tl_survey_result
 */
$GLOBALS['TL_DCA']['tl_survey_result'] = array
(

	// Config
	'config' => array
	(
		'ptable'                      => 'tl_survey',
		'doNotCopyRecords'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'qid' => 'index'
			)
		)
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
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pin' => array
		(
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'uid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'qid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'result' => array
		(
			'sql'                     => "text NULL"
		),
	)
);

?>