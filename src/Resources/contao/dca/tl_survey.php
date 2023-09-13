<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */

use Contao\BackendUser;

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
                'icon' => 'bundles/hschottmsurvey/images/key.svg',
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
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
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
        '__selector__' => ['access', 'limit_groups', 'useResultCategories', 'sendConfirmationMail', 'sendConfirmationMailAlternate', 'addConfirmationMailAttachments', 'addConfirmationMailAlternateAttachments', 'useNotifications'],
        'default' => '{title_legend},title,author,description,language,duration;{activation_legend},online_start,online_end',
        'anon' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,jumpto,useResultCategories',
        'anoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,allow_autostart,jumpto,useResultCategories',
        'nonanoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie,limit_groups;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{sendconfirmationmail_legend:hide},sendConfirmationMail,sendConfirmationMailAlternate;{misc_legend},allowback,immediate_start,allow_autostart,jumpto,useResultCategories',
    ],

    // Palettes
    'subpalettes' => [
        'limit_groups' => 'allowed_groups,useNotifications',
        'useResultCategories' => 'resultCategories',
        'sendConfirmationMail' => 'confirmationMailRecipientField,confirmationMailRecipient,confirmationMailSender,confirmationMailReplyto,confirmationMailSubject,confirmationMailText,confirmationMailTemplate,addConfirmationMailAttachments',
        'sendConfirmationMailAlternate' => 'confirmationMailAlternateCondition,confirmationMailAlternateRecipient,confirmationMailAlternateSender,confirmationMailAlternateReplyto,confirmationMailAlternateSubject,confirmationMailAlternateText,confirmationMailAlternateTemplate,addConfirmationMailAlternateAttachments',
        'addConfirmationMailAttachments' => 'confirmationMailAttachments',
        'addConfirmationMailAlternateAttachments' => 'confirmationMailAlternateAttachments',
        'useNotifications' => 'invitationNotificationId,reminderNotificationId,surveyPage',
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
        'duration' => [
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['maxlength' => 3, 'rgxp' => 'natural', 'minval' => 0, 'maxval' => 255, 'nospace' => true, 'tl_class' => 'w50'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
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
            'relation' => ['type' => 'hasMany', 'load' => 'lazy'],
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
            'eval' => ['tl_class' => 'w33'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'immediate_start' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w33'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'allow_autostart' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w33'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'jumpto' => [
            'exclude' => true,
            'inputType' => 'pageTree',
            'explanation' => 'jumpTo',
            'eval' => ['fieldType' => 'radio', 'helpwizard' => true, 'tl_class' => 'clr'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'useResultCategories' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'resultCategories' => [
            'exclude' => true,
            'inputType' => 'group',
            'palette' => ['category', 'id'],
            'fields' => [
                'id' => [
                    'inputType' => 'text',
                    'eval' => ['readonly' => true, 'tl_class' => 'w50'],
                ],
                'category' => [
                    'inputType' => 'text',
                    'eval' => ['tl_class' => 'w50'],
                ],
            ],
            'sql' => [
                'type' => 'blob',
                'notnull' => false,
            ],
        ],
        // old notation for mail refactored
        'sendConfirmationMail' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMail'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['helpwizard' => true, 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'sendConfirmationMailAlternate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['sendConfirmationMailAlternate'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['helpwizard' => true, 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'confirmationMailAlternateCondition' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateCondition'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailRecipientField' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipientField'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'confirmationMailRecipient' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailRecipient'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailAlternateRecipient' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateRecipient'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailSender' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSender'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailAlternateSender' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSender'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailReplyto' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailReplyto'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailAlternateReplyto' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateReplyto'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailSubject' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailSubject'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailAlternateSubject' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateSubject'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'confirmationMailText' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailText'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'textarea',
            'eval' => ['mandatory' => true, 'rows' => 15, 'allowHTML' => false, 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'confirmationMailAlternateText' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateText'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'textarea',
            'eval' => ['mandatory' => true, 'rows' => 15, 'allowHTML' => false, 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'confirmationMailTemplate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailTemplate'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'fileTree',
            'eval' => ['helpwizard' => false, 'files' => true, 'fieldType' => 'radio', 'extensions' => 'htm,html,txt,tpl'],
            'sql' => 'binary(16) NULL',
        ],
        'confirmationMailAlternateTemplate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAlternateTemplate'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'fileTree',
            'eval' => ['helpwizard' => false, 'files' => true, 'fieldType' => 'radio', 'extensions' => 'htm,html,txt,tpl'],
            'sql' => 'binary(16) NULL',
        ],
        'sendFormattedMail' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['sendFormattedMail'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'formattedMailRecipient' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['recipient'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'extnd', 'tl_class' => 'w50'],
            'sql' => 'text NULL',
        ],
        'formattedMailSubject' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['subject'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],

        'formattedMailText' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['formattedMailText'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'textarea',
            'eval' => ['rows' => 15, 'allowHTML' => false, 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'formattedMailTemplate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['formattedMailTemplate'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'fileTree',
            'eval' => ['helpwizard' => false, 'files' => true, 'fieldType' => 'radio', 'extensions' => 'htm,html,txt,tpl'],
            'sql' => 'binary(16) NULL',
        ],
        'formattedMailSkipEmpty' => [
            'exclude' => true,
            'filter' => false,
            'inputType' => 'checkbox',
            'sql' => "char(1) NOT NULL default ''",
        ],

        'addConfirmationMailAttachments' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'addConfirmationMailAlternateAttachments' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'confirmationMailAttachments' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['confirmationMailAttachments'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'fileTree',
            'eval' => ['fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'multiple' => true, 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'confirmationMailAlternateAttachments' => [
            'exclude' => true,
            'filter' => false,
            'inputType' => 'fileTree',
            'eval' => ['fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'multiple' => true, 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        // nc extension for survey
        'useNotifications' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'invitationNotificationId' => [
            'exclude' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_nc_notification.title',
            'flag' => 1, // 1 Sort by initial letter ascending
            'eval' => ['tl_class' => 'w50', 'chosen' => true, 'includeBlankOption' => true],
            'sql' => "int(10) NOT NULL default '0'",
        ],
        'reminderNotificationId' => [
            'exclude' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_nc_notification.title',
            'flag' => 1, // 1 Sort by initial letter ascending
            'eval' => ['tl_class' => 'w50', 'chosen' => true, 'includeBlankOption' => true],
            'sql' => "int(10) NOT NULL default '0'",
        ],
        'surveyPage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey']['surveyPage'],
            'inputType' => 'pageTree',
            'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'clr'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];
