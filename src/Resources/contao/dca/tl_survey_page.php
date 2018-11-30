<?php

use Hschottm\SurveyBundle\Model\SurveyResultModel;

$found = (strlen(\Input::get('id'))) ? SurveyResultModel::findByPid(\Input::get('id')) : null;
$hasData = (null != $found && 0 < $found->numRows) ? true : false;

if ($hasData)
{
	/**
	 * Table tl_survey_question
	 */
	$GLOBALS['TL_DCA']['tl_survey_page'] = array
	(

		// Config
		'config' => array
		(
			'dataContainer'               => 'Table',
			'ptable'                      => 'tl_survey',
			'ctable'                      => array('tl_survey_question'),
			'notEditable'                 => true,
			'closed'                      => true,
			'sql' => array
			(
				'keys' => array
				(
					'id' => 'primary',
					'pid' => 'index'
				)
			)
		)
	);
}
else
{
	/**
	 * Table tl_survey_question
	 */
	$GLOBALS['TL_DCA']['tl_survey_page'] = array
	(
		// Config
		'config' => array
		(
			'dataContainer'               => 'Table',
			'ptable'                      => 'tl_survey',
			'ctable'                      => array('tl_survey_question'),
			'switchToEdit'                => true,
			'enableVersioning'            => true,
			'sql' => array
			(
				'keys' => array
				(
					'id' => 'primary',
					'pid' => 'index'
				)
			)
		)
	);
}

// List
$GLOBALS['TL_DCA']['tl_survey_page']['list'] = array
(
	'sorting' => array
	(
		'mode'                    => 4,
		'filter'                  => true,
		'fields'                  => array('sorting'),
		'panelLayout'             => 'search,filter,limit',
		'headerFields'            => array('title', 'tstamp', 'description'),
		'child_record_callback'   => array('Hschottm\SurveyBundle\Backend\SurveyPagePreview', 'compilePreview')
	),
	'operations' => array
	(
		'edit' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_survey_page']['edit'],
			'href'                => 'table=tl_survey_question',
			'icon'                => 'edit.gif',
			'button_callback'     => array('tl_survey_page', 'editPage')
		),
		'copy' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_survey_page']['copy'],
			'href'                => 'act=paste&mode=copy',
			'icon'                => 'copy.gif',
			'button_callback'     => array('tl_survey_page', 'copyPage')
		),
		'cut' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_survey_page']['cut'],
			'href'                => 'act=paste&mode=cut',
			'icon'                => 'cut.gif',
			'attributes'          => 'onclick="Backend.getScrollOffset();"',
			'button_callback'     => array('tl_survey_page', 'cutPage')
		),
		'delete' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_survey_page']['delete'],
			'href'                => 'act=delete',
			'icon'                => 'delete.gif',
			'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			'button_callback'     => array('tl_survey_page', 'deletePage')
		),
		'show' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_survey_page']['show'],
			'href'                => 'act=show',
			'icon'                => 'show.gif'
		)
	)
);

// Palettes
$GLOBALS['TL_DCA']['tl_survey_page']['palettes'] = array
(
	'default'               => '{title_legend},title,description;{intro_legend},introduction;{template_legend},page_template',
);

// Fields
$GLOBALS['TL_DCA']['tl_survey_page']['fields'] = array
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
	'sorting' => array
	(
		'sql'                     => "int(10) unsigned NOT NULL default '0'"
	),
	'title' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_survey_page']['title'],
		'search'                  => true,
		'sorting'                 => true,
		'filter'                  => true,
		'flag'                    => 1,
		'inputType'               => 'text',
		'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'insertTag'=>true),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'description' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_survey_page']['description'],
		'search'                  => true,
		'inputType'               => 'textarea',
		'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;'),
		'sql'                     => "text NULL"
	),
	'language' => array
	(
		'sql'                     => "varchar(32) NOT NULL default ''"
	),
	'introduction' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_survey_page']['introduction'],
		'default'                 => '',
		'search'                  => true,
		'inputType'               => 'textarea',
		'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte'=>'tinyMCE'),
		'sql'                     => "text NOT NULL"
	),
	'page_template' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_survey_page']['page_template'],
		'default'                 => 'survey_questionblock',
		'inputType'               => 'select',
		'options_callback'        => array('tl_survey_page', 'getSurveyTemplates'),
		'eval'                    => array('tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default 'survey_questionblock'"
	),
	'pagetype' => array
	(
		'sql'                     => "varchar(30) NOT NULL default 'standard'"
	),
);


/**
 * Class tl_survey_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_survey_page extends Backend
{
	protected $hasData = null;

	/**
	 * Return all survey templates as array
	 * @param object
	 * @return array
	 */
	public function getSurveyTemplates(DataContainer $dc)
	{
		if (version_compare(VERSION.BUILD, '2.9.0', '>='))
		{
			return $this->getTemplateGroup('survey_', $dc->activeRecord->pid);
		}
		else
		{
			return $this->getTemplateGroup('survey_');
		}
	}

	protected function hasData()
	{
		if (is_null($this->hasData))
		{
			$objResult = $this->Database->prepare("SELECT * FROM tl_survey_result WHERE pid=?")
				->execute(\Input::get('id'));
			$this->hasData = $objResult->numRows > 0;
		}
		return $this->hasData;
	}

	/**
	 * Return the edit page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editPage($row, $href, $label, $title, $icon, $attributes)
	{
		if ($this->hasData())
		{
			return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
		else
		{
			return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	}

	/**
	 * Return the copy page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPage($row, $href, $label, $title, $icon, $attributes)
	{
		if ($this->hasData())
		{
			return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
		else
		{
			return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	}

	/**
	 * Return the cut page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutPage($row, $href, $label, $title, $icon, $attributes)
	{
		if ($this->hasData())
		{
			return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
		else
		{
			return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	}

	/**
	 * Return the delete page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deletePage($row, $href, $label, $title, $icon, $attributes)
	{
		if ($this->hasData())
		{
			return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}
		else
		{
			return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	}
}
