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

$GLOBALS['TL_DCA']['tl_survey_pin_tan'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_survey',
        'doNotCopyRecords' => true,
        'closed' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
                'pin' => 'index',
                'tan' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['tan'],
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['tan', 'tstamp', 'used'],
            'format' => '%s::%s::%s',
        ],
        'global_operations' => [
            'createtan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'],
                'href' => 'key=createtan',
                'icon' => 'bundles/hschottmsurvey/images/key.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'exporttan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'],
                'href' => 'key=exporttan',
                'class' => 'header_export',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'invite' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite'],
                'href' => 'key=invite',
                'class' => 'header_invite',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'remind' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind'],
                'href' => 'key=remind',
                'class' => 'header_remind',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes' => [],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        // 0 means: the TAN is valid for all member
        'member_id' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pin' => [
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'tan' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'],
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'insertTag' => true],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'used' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'],
            'filter' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sorting' => true,
            'flag' => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'insertTag' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'invited' => [
            'sorting' => true,
            'flag' => 6,
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'reminded' => [
            'sorting' => true,
            'flag' => 6,
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'reminded_count' => [
            'sorting' => true,
            'flag' => 6,
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];
