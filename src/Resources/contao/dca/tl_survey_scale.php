<?php

/**
 * Table tl_survey_scale
 */
$GLOBALS['TL_DCA']['tl_survey_scale'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_survey_scale_folder',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'filter'                  => true,
			'fields'                  => array('title'),
			'panelLayout'             => 'search,filter,limit',
			'flag'                    => 11,
			'headerFields'            => array('title', 'tstamp', 'description'),
			'child_record_callback'   => array('tl_survey_scale', 'compilePreview')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_scale']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_scale']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_scale']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_scale']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_scale']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'               => '{title_legend},title,description,language;{scale_legend},scale',
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
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_scale']['title'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_scale']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;'),
			'sql'                     => "text NULL"
		),
		'scale' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_scale']['scale'],
			'exclude'                 => true,
			'inputType'               => 'textwizard',
			'eval'                    => array('allowHtml'=>true, 'mandatory' => true),
			'sql'                     => "blob NULL"
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_scale']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getLanguages(),
			'eval'                    => array('includeBlankOption'=>true),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
	)
);

/**
 * Class tl_survey_scale
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_survey_scale extends Backend
{
	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	public function compilePreview($row, $blnWriteToFile=false)
	{
		$result = '<p><strong>' . $row['title'] . '</strong></p>';
		$result .= "<ol>";
		$answers = deserialize($row['scale'], true);
		foreach ($answers as $answer)
		{
			$result .= '<li>' . specialchars($answer) . '</li>';
		}
		$result .= "</ol>";
		return $result;
	}
}
