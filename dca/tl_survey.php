<?php

$found = (strlen(\Input::get('id'))) ? SurveyResultModel::findByPid(\Input::get('id')) : null;
$hasData = (null != $found && 0 < $found->numRows) ? true : false;

/**
 * Table tl_survey
 */
$GLOBALS['TL_DCA']['tl_survey'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_survey_page','tl_survey_participant','tl_survey_result','tl_survey_pin_tan'),
		'switchToEdit'                => true,
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
			'mode'                    => 2,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
			'label_callback'          => array('tl_survey', 'addIcon')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['edit'],
				'href'                => 'table=tl_survey_page',
				'icon'                => 'edit.gif'
			),
			'pintan' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['pintan'],
				'href'                => 'table=tl_survey_pin_tan',
				'icon'                => 'system/modules/survey_ce/assets/pintan.png'
			),
			'participants' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['participants'],
				'href'                => 'table=tl_survey_participant',
				'icon'                => 'system/modules/survey_ce/assets/participants.png'
			),
			'statistics' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['statistics'],
				'href'                => 'key=cumulated',
				'icon'                => 'system/modules/survey_ce/assets/statistics.png'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_survey']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('access','limit_groups'),
		'default'                     => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end',
		'anon'                        => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,{misc_legend},allowback,jumpto',
		'anoncode'                    => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,{misc_legend},allowback,jumpto',
		'nonanoncode'                 => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie,limit_groups;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,{misc_legend},allowback,jumpto',
	),

	// Palettes
	'subpalettes' => array
	(
		'limit_groups'                => 'allowed_groups'
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
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'insertTag'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getLanguages(),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['author'],
			'default'                 => BackendUser::getInstance()->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'online_start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['online_start'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'online_end' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['online_end'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;','tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'access' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['access'],
			'default'                 => 'anon',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('anon', 'anoncode', 'nonanoncode'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_survey']['access'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class' => 'w50 m12'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'usecookie' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['usecookie'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'limit_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['limit_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'clr'),
			'sql'                     => "char(1) NOT NULL default '0'"
		),
		'show_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['show_title'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12'),
			'sql'                     => "char(1) NOT NULL default '1'"
		),
		'show_cancel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['show_cancel'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12'),
			'sql'                     => "char(1) NOT NULL default '1'"
		),
		'allowed_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['allowed_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true),
			'sql'                     => "blob NULL"
		),
		'introduction' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['introduction'],
			'default'                 => '',
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE'),
			'sql'                     => "text NOT NULL"
		),
		'finalsubmission' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['finalsubmission'],
			'default'                 => &$GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE'),
			'sql'                     => "text NOT NULL"
		),
		'allowback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['allowback'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'jumpto' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['jumpto'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'explanation'             => 'jumpTo',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true, 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
	)
);

$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'sendConfirmationMail';
$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'addConfirmationMailAttachments';

array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']),
	array('sendConfirmationMail' => 'confirmationMailRecipientField,confirmationMailRecipient,confirmationMailSender,confirmationMailReplyto,confirmationMailSubject,confirmationMailText,confirmationMailTemplate,confirmationMailSkipEmpty,addConfirmationMailAttachments')
);
array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']),
	array('addConfirmationMailAttachments' => 'confirmationMailAttachments')
);

$GLOBALS['TL_DCA']['tl_survey']['fields']['sendConfirmationMail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMail'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('helpwizard'=>true,'submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailRecipientField'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipientField'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'select',
	'options_callback'        => array('tl_survey', 'getEmailFormFields'),
	'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailRecipient'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipient'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailSender'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSender'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailReplyto'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailReplyto'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailSubject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSubject'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailText'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailText'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'textarea',
	'eval'                    => array('mandatory'=>true, 'rows'=>15, 'allowHTML'=>false, 'tl_class' => 'clr'),
	'sql'                     => "text NULL"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailTemplate'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'fileTree',
	'eval'                    => array('helpwizard'=>false,'files'=>true, 'fieldType'=>'radio', 'extensions' => 'htm,html,txt,tpl'),
	'sql'                     => "binary(16) NULL"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailSkipEmpty'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['skipEmtpy'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_survey']['fields']['sendFormattedMail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['sendFormattedMail'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_survey']['fields']['formattedMailRecipient'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['recipient'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'tl_class'=>'w50'),
	'sql'                     => "text NULL"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['formattedMailSubject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['subject'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_survey']['fields']['formattedMailText'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['formattedMailText'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'textarea',
	'eval'                    => array('rows'=>15, 'allowHTML'=>false, 'tl_class' => 'clr'),
	'sql'                     => "text NULL"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['formattedMailTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['formattedMailTemplate'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'fileTree',
	'eval'                    => array('helpwizard'=>false,'files'=>true, 'fieldType'=>'radio', 'extensions' => 'htm,html,txt,tpl'),
	'sql'                     => "binary(16) NULL"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['formattedMailSkipEmpty'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['skipEmtpy'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_survey']['fields']['addConfirmationMailAttachments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAttachments'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAttachments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAttachments'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'fileTree',
	'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'multiple' => true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

if ($hasData)
{
	$GLOBALS['TL_DCA']['tl_survey']['fields']['access']['eval']['disabled'] = 'disabled';
}

/**
 * Class tl_survey
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_survey extends Backend
{
	/**
	 * Load database object
	 */
	protected function __construct()
	{
		parent::__construct();
		
		// somehow dirty patch to allow going back if someone clicks back on a survey question list
		if (strpos($this->getReferer(ENCODE_AMPERSANDS), 'tl_survey_question'))
		{
			if (preg_match("/id=(\\d+)/", $this->getReferer(ENCODE_AMPERSANDS), $matches))
			{
				$page_id = $matches[1];
				$survey_id = $this->Database->prepare("SELECT pid FROM tl_survey_page WHERE id=?")
					->execute($page_id)
					->fetchEach('pid');
				if ($survey_id[0] > 0)
				{
					$this->redirect($this->addToUrl('table=tl_survey_page&id=' . $survey_id[0]));
				}
			}
		}
	}

	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		return sprintf('<div class="list_icon" style="background-image:url(\'system/modules/survey_ce/assets/survey.png\');">%s</div>', $label);
	}
	
	public function getEmailFormFields()
	{
		$fields = array();
		
		// Get all form fields which can be used to define recipient of confirmation mail
		$objFields = \Database::getInstance()->prepare("SELECT tl_survey_question.id,tl_survey_question.title FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? AND tl_survey_question.questiontype=? ORDER BY tl_survey_question.title ASC")
			->execute(\Input::get('id'), 'openended');

		$fields[] = '-';
		while ($objFields->next())
		{
			$k = $objFields->id;
			if (strlen($k))
			{
				$v = $objFields->title;
				$v = strlen($v) ? $v.' ['.$k.']' : $k;
				$fields[$k] =$v;
			}
		}

		return $fields;
	}
}

