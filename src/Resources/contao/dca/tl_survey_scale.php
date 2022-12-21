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

use Contao\Backend;
use Contao\StringUtil;

$GLOBALS['TL_DCA']['tl_survey_scale'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_survey_scale_folder',
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
            'mode' => 4,
            'filter' => true,
            'fields' => ['title'],
            'panelLayout' => 'search,filter,limit',
            'flag' => 11,
            'headerFields' => ['title', 'tstamp', 'description'],
            'child_record_callback' => ['tl_survey_scale', 'compilePreview'],
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
                'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'cut' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['cut'],
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},title,description,language;{scale_legend},scale',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['title'],
            'search' => true,
            'sorting' => true,
            'filter' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'description' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['description'],
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['allowHtml' => true, 'style' => 'height:80px;'],
            'sql' => 'text NULL',
        ],
        'scale' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['scale'],
            'exclude' => true,
            'inputType' => 'textwizard',
            'eval' => ['allowHtml' => true, 'mandatory' => true],
            'sql' => 'blob NULL',
        ],
        'language' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_scale']['language'],
            'default' => $GLOBALS['TL_LANGUAGE'],
            'filter' => true,
            'inputType' => 'select',
            'options' => $this->getLanguages(),
            'eval' => ['includeBlankOption' => true],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
    ],
];

/**
 * Class tl_survey_scale.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
class tl_survey_scale extends Backend
{
    /**
     * Compile format definitions and return them as string.
     *
     * @param array
     * @param bool
     * @param mixed $row
     * @param mixed $blnWriteToFile
     *
     * @return string
     */
    public function compilePreview($row, $blnWriteToFile = false)
    {
        $result = '<p><strong>'.$row['title'].'</strong></p>';
        $result .= '<ol>';
        $answers = deserialize($row['scale'], true);

        foreach ($answers as $answer) {
            $result .= '<li>'.StringUtil::specialchars($answer).'</li>';
        }
        $result .= '</ol>';

        return $result;
    }
}
