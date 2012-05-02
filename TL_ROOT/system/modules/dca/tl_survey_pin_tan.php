<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Table tl_survey_pin_tan
 */
$GLOBALS['TL_DCA']['tl_survey_pin_tan'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_survey',
		'doNotCopyRecords'            => true,
		'closed'                      => true,
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('tan'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter,search;limit'
		),
		'label' => array
		(
			'fields'                  => array('tan','tstamp','used'),
			'format'                  => '%s::%s::%s',
			'label_callback'          => array('tl_survey_pin_tan', 'getLabel')
		),
		'global_operations' => array
		(
			'createtan' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'],
				'href'                => 'key=createtan',
				'class'               => 'header_createtan',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'exporttan' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'],
				'href'                => 'key=exporttan',
				'class'               => 'header_export',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
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
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['show'],
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
		'tan' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'],
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>16, 'insertTag'=>true)
			),
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tstamp'],
			'sorting'                 => true,
			'flag'                    => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>16, 'insertTag'=>true)
		),
		'used' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'],
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>16)
		),
		'surveyPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['surveyPage'],
			'inputType'               => 'pageTree',
			'eval'                    => array('mandatory'=>false, 'fieldType' => 'radio')
		),
	)
);

/**
 * Class tl_survey_pin_tan
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_survey_pin_tan extends Backend
{
	public function getLabel($row, $label)
	{
		preg_match("/^(.*?)::(.*?)::(.*?)$/", $label, $matches);
		if ($matches[3])
		{
			// tan is used
			$used = '<img src="system/modules/survey_ce/html/images/tan_used.png" alt="' . $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'] . '" title="' . $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'] . '" />';
		}
		else
		{
			$used = '<img src="system/modules/survey_ce/html/images/tan_new.png" alt="' . $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'] . '" title="' . $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'] . '" />';
		}
		return sprintf('<div>%s <strong>%s</strong> (%s)</div>', $used, $matches[1], $matches[2]);
	}
}

?>