<?php

use Contao\DC_Table;
use Contao\DataContainer;

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_DCA']['tl_survey_pin_tan'] = [
    // Config
    'config' => [
        'dataContainer' => DC_Table::class,
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
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['tan'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['tan', 'tstamp', 'used'],
            'format' => '%s::%s::%s',
            //'label_callback' => ['tl_survey_pin_tan', 'getLabel'],
        ],
        'global_operations' => [
            'createtan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'],
                'href' => 'key=createtan',
                'class' => 'header_createtan',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'exporttan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'],
                'href' => 'key=exporttan',
                'class' => 'header_export',
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
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pin' => [
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'tan' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'],
            'search' => true,
            'sorting' => true,
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
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
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tstamp'],
            'sorting' => true,
            'flag' => DataContainer::SORT_DAY_DESC, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'insertTag' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];

/**
 * Class tl_survey_pin_tan.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
/*
class tl_survey_pin_tan extends Backend
{
    public function getLabel($row, $label)
    {
        preg_match('/^(.*?)::(.*?)::(.*?)$/', $label, $matches);
        if ($matches[3]) {
            // tan is used
            $used = '<img src="bundles/hschottmsurvey/images/tan_used.png" alt="'.$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'].'" title="'.$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_used'].'" />';
        } else {
            $used = '<img src="bundles/hschottmsurvey/images/tan_new.png" alt="'.$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'].'" title="'.$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan_new'].'" />';
        }

        return sprintf('<div>%s <strong>%s</strong> (%s)</div>', $used, $matches[1], $matches[2]);
    }
}
    */
