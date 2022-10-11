<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Database;
use Contao\Input;
use Doctrine\DBAL\Platforms\MySqlPlatform;

$found = (\strlen(Input::get('id'))) ? \Hschottm\SurveyBundle\SurveyResultModel::findByPid(Input::get('id')) : null;
 $hasData = (null != $found && 0 < $found->count()) ? true : false;

/*
 * Table tl_survey
 */
$GLOBALS['TL_DCA']['tl_survey'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ctable' => ['tl_survey_page', 'tl_survey_participant', 'tl_survey_result', 'tl_survey_pin_tan'],
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['title'],
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
            'label_callback' => ['tl_survey', 'addIcon'],
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['edit'],
                'href' => 'table=tl_survey_page',
                'icon' => 'edit.svg',
            ],
            'pintan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['pintan'],
                'href' => 'table=tl_survey_pin_tan',
                'icon' => 'bundles/hschottmsurvey/images/pintan.png',
            ],
            'participants' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['participants'],
                'href' => 'table=tl_survey_participant',
                'icon' => 'bundles/hschottmsurvey/images/participants.png',
            ],
            'statistics' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['statistics'],
                'href' => 'key=cumulated',
                'icon' => 'bundles/hschottmsurvey/images/statistics.png',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        '__selector__' => ['access', 'limit_groups', 'useResultCategories'],
        'default' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end',
        'anon' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,jumpto,useResultCategories',
        'anoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,jumpto,useResultCategories',
        'nonanoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie,limit_groups;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,jumpto,useResultCategories',
    ],

    // Palettes
    'subpalettes' => [
        'limit_groups' => 'allowed_groups',
        'useResultCategories' => 'resultCategories',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'insertTag' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'language' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['language'],
            'default' => $GLOBALS['TL_LANGUAGE'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => $this->getLanguages(),
            'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'author' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['author'],
            'default' => BackendUser::getInstance()->id,
            'exclude' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_user.name',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "smallint(5) unsigned NOT NULL default '0'",
        ],
        'online_start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['online_start'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 32, 'rgxp' => 'datim', 'datepicker' => $this->getDatePickerString(), 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'online_end' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['online_end'],
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 32, 'rgxp' => 'datim', 'datepicker' => $this->getDatePickerString(), 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'description' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['description'],
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['allowHtml' => true, 'style' => 'height:80px;', 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'access' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['access'],
            'default' => 'anon',
            'exclude' => true,
            'inputType' => 'radio',
            'options' => ['anon', 'anoncode', 'nonanoncode'],
            'reference' => &$GLOBALS['TL_LANG']['tl_survey']['access'],
            'eval' => ['helpwizard' => true, 'submitOnChange' => true, 'tl_class' => 'w50 m12'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'usecookie' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['usecookie'],
            'filter' => true,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'limit_groups' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['limit_groups'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
            'sql' => "char(1) NOT NULL default '0'",
        ],
        'show_title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['show_title'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default '1'",
        ],
        'show_cancel' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['show_cancel'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default '1'",
        ],
        'allowed_groups' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['allowed_groups'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'foreignKey' => 'tl_member_group.name',
            'eval' => ['multiple' => true],
            'sql' => 'blob NULL',
        ],
        'introduction' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['introduction'],
            'default' => '',
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['allowHtml' => true, 'style' => 'height:80px;', 'rte' => 'tinyMCE'],
            'sql' => 'text NOT NULL',
        ],
        'finalsubmission' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['finalsubmission'],
            'default' => &$GLOBALS['TL_LANG']['MSC']['survey_finalsubmission'],
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['allowHtml' => true, 'style' => 'height:80px;', 'rte' => 'tinyMCE'],
            'sql' => 'text NOT NULL',
        ],
        'allowback' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['allowback'],
            'filter' => true,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "char(1) NOT NULL default ''",
        ],
    'immediate_start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['immediate_start'],
            'filter' => true,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'jumpto' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['jumpto'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'explanation' => 'jumpTo',
            'eval' => ['fieldType' => 'radio', 'helpwizard' => true, 'tl_class' => 'clr'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    'surveyPage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['surveyPage'],
            'inputType' => 'pageTree',
            'eval' => ['mandatory' => false, 'fieldType' => 'radio'],
        ],
        'useResultCategories' => [
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'resultCategories' => [
            'exclude'   => true,
            'inputType' => 'group',
            'palette' => ['category', 'id',],
            'fields' => [
                'id' => [
                    'inputType' => 'text',
                    'eval' => ['readonly' => true, 'tl_class' => 'w50',],
                ],
                'category' => [
                    'inputType' => 'text',
                    'eval' => ['tl_class' => 'w50',],
                ],
            ],
            'sql' => [
                'type' => 'blob',
                'length' => MySqlPlatform::LENGTH_LIMIT_BLOB,
                'notnull' => false,
            ],
        ],
    ],
];

$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'sendConfirmationMail';
$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'sendConfirmationMailAlternate';
$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'addConfirmationMailAttachments';
$GLOBALS['TL_DCA']['tl_survey']['palettes']['__selector__'][] = 'addConfirmationMailAlternateAttachments';

array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']), array('sendConfirmationMail' => 'confirmationMailRecipientField,confirmationMailRecipient,confirmationMailSender,confirmationMailReplyto,confirmationMailSubject,confirmationMailText,confirmationMailTemplate,addConfirmationMailAttachments'));
array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']), array('sendConfirmationMailAlternate' => 'confirmationMailAlternateCondition,confirmationMailAlternateRecipient,confirmationMailAlternateSender,confirmationMailAlternateReplyto,confirmationMailAlternateSubject,confirmationMailAlternateText,confirmationMailAlternateTemplate,addConfirmationMailAlternateAttachments'));
array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']), array('addConfirmationMailAttachments' => 'confirmationMailAttachments'));
array_insert($GLOBALS['TL_DCA']['tl_survey']['subpalettes'], count($GLOBALS['TL_DCA']['tl_survey']['subpalettes']), array('addConfirmationMailAlternateAttachments' => 'confirmationMailAlternateAttachments'));

$GLOBALS['TL_DCA']['tl_survey']['fields']['sendConfirmationMail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMail'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('helpwizard'=>true,'submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['sendConfirmationMailAlternate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMailAlternate'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('helpwizard'=>true,'submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateCondition'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateCondition'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateRecipient'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateRecipient'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateSender'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSender'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateReplyto'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateReplyto'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateSubject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSubject'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateText'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateText'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateTemplate'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'fileTree',
	'eval'                    => array('helpwizard'=>false,'files'=>true, 'fieldType'=>'radio', 'extensions' => 'htm,html,txt,tpl'),
	'sql'                     => "binary(16) NULL"
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['addConfirmationMailAlternateAttachments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['addConfirmationMailAlternateAttachments'],
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
$GLOBALS['TL_DCA']['tl_survey']['fields']['confirmationMailAlternateAttachments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateAttachments'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'fileTree',
	'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'multiple' => true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);


if ($hasData) {
    $GLOBALS['TL_DCA']['tl_survey']['fields']['access']['eval']['disabled'] = 'disabled';
}

/**
 * Class tl_survey.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
class tl_survey extends Backend
{
    /**
     * Load database object.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Add an image to each record.
     *
     * @param array
     * @param string
     * @param mixed $row
     * @param mixed $label
     *
     * @return string
     */
    public function addIcon($row, $label)
    {
        return sprintf('<div class="list_icon" style="background-image:url(\'bundles/hschottmsurvey/images/survey-outline.svg\');">%s</div>', $label);
    }

    public function getEmailFormFields()
  	{
  		$fields = array();

  		// Get all form fields which can be used to define recipient of confirmation mail
  		$objFields = Database::getInstance()->prepare("SELECT tl_survey_question.id,tl_survey_question.title FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? AND tl_survey_question.questiontype=? ORDER BY tl_survey_question.title ASC")
  			->execute(Input::get('id'), 'openended');

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
