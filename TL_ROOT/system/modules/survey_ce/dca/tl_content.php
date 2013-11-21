<?php

/**
 * Table tl_content
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['survey'] = '{type_legend},type,headline;{survey_legend},survey;{template_legend:hide},surveyTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['fields']['survey'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['survey'],
	'exclude'                 => true,
	'inputType'               => 'radio',
	'foreignKey'              => 'tl_survey.title',
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['surveyTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['surveyTpl'],
	'default'                 => 'ce_survey',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_survey', 'getSurveyTemplates'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

class tl_content_survey extends tl_content
{
	/**
	 * Return all survey templates as array
	 * @param object
	 * @return array
	 */
	public function getSurveyTemplates(DataContainer $dc)
	{
		if (version_compare(VERSION.BUILD, '2.9.0', '>=')) 
		{
			return $this->getTemplateGroup('ce_survey', $dc->activeRecord->pid);
		} 
		else 
		{
			return $this->getTemplateGroup('ce_survey');
		}
	}
}

