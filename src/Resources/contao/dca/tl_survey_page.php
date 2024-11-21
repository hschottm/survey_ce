<?php

/*
 * @copyright  Helmut Schottmüller 2005-2024 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

use Hschottm\SurveyBundle\SurveyResultModel;
use Contao\Input;
use Contao\DC_Table;
use Contao\DataContainer;
use Contao\Controller;

 $found = (\strlen(Input::get('id'))) ? SurveyResultModel::findByPid(Input::get('id')) : null;
 $hasData = (null != $found && 0 < $found->count()) ? true : false;

if ($hasData) {
    $GLOBALS['TL_DCA']['tl_survey_page'] = [
        // Config
        'config' => [
            'dataContainer' => DC_Table::class,
            'ptable' => 'tl_survey',
            'ctable' => ['tl_survey_question'],
            'notEditable' => true,
            'closed' => true,
            'sql' => [
                'keys' => [
                    'id' => 'primary',
                    'pid' => 'index',
                ],
            ],
        ],
    ];
} else {
    $GLOBALS['TL_DCA']['tl_survey_page'] = [
        // Config
        'config' => [
            'dataContainer' => DC_Table::class,
            'ptable' => 'tl_survey',
            'ctable' => ['tl_survey_question'],
            'switchToEdit' => true,
            'enableVersioning' => true,
            'sql' => [
                'keys' => [
                    'id' => 'primary',
                    'pid' => 'index',
                ],
            ],
        ],
    ];
}

// List
$GLOBALS['TL_DCA']['tl_survey_page']['list'] = [
    'sorting' => [
        'mode' => DataContainer::MODE_PARENT,
        'filter' => true,
        'fields' => ['sorting'],
        'panelLayout' => 'search,filter,limit',
        'headerFields' => ['title', 'tstamp', 'description'],
        //'child_record_callback' => ['\Hschottm\SurveyBundle\SurveyPagePreview', 'compilePreview'],
    ],
    'label' => array
    (
        'fields'                  => array('title', 'description'),
        //'label_callback'          => array('tl_log', 'colorize')
    ),
    'operations' => [
        'edit' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['edit'],
            'href' => 'table=tl_survey_question',
            'icon' => 'edit.svg',
            'button_callback' => [\Hschottm\SurveyBundle\SurveyPageHelper::class, 'editPage'],
        ],
        'copy' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['copy'],
            'href' => 'act=paste&mode=copy',
            'icon' => 'copy.svg',
            'button_callback' => [\Hschottm\SurveyBundle\SurveyPageHelper::class, 'copyPage'],
        ],
        'cut' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['cut'],
            'href' => 'act=paste&mode=cut',
            'icon' => 'cut.svg',
            'attributes' => 'onclick="Backend.getScrollOffset();"',
            'button_callback' => [\Hschottm\SurveyBundle\SurveyPageHelper::class, 'cutPage'],
        ],
        'delete' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['delete'],
            'href' => 'act=delete',
            'icon' => 'delete.svg',
            'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            'button_callback' => [\Hschottm\SurveyBundle\SurveyPageHelper::class, 'deletePage'],
        ],
        'show' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['show'],
            'href' => 'act=show',
            'icon' => 'show.svg',
        ],
    ],
];

// Palettes
$GLOBALS['TL_DCA']['tl_survey_page']['palettes'] = [
    'default' => '{title_legend},title,description;{intro_legend},introduction;{template_legend},page_template',
];
//    'default' => '{title_legend},title,description;{intro_legend},introduction;{condition_legend},conditions;{template_legend},page_template',

// Fields
$GLOBALS['TL_DCA']['tl_survey_page']['fields'] = [
    'id' => [
        'sql' => 'int(10) unsigned NOT NULL auto_increment',
    ],
    'tstamp' => [
        'sql' => "int(10) unsigned NOT NULL default '0'",
    ],
    'pid' => [
        'sql' => "int(10) unsigned NOT NULL default '0'",
    ],
    'sorting' => [
        'sql' => "int(10) unsigned NOT NULL default '0'",
    ],
    'title' => [
        'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['title'],
        'search' => true,
        'sorting' => true,
        'filter' => true,
        'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'maxlength' => 255, 'insertTag' => true],
        'sql' => "varchar(255) NOT NULL default ''",
    ],
    'description' => [
        'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['description'],
        'search' => true,
        'inputType' => 'textarea',
        'eval' => ['allowHtml' => true, 'style' => 'height:80px;'],
        'sql' => 'text NULL',
    ],
    'language' => [
        'sql' => "varchar(32) NOT NULL default ''",
    ],
    'introduction' => [
        'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['introduction'],
        'default' => '',
        'search' => true,
        'inputType' => 'textarea',
        'eval' => ['allowHtml' => true, 'style' => 'height:80px;', 'rte' => 'tinyMCE'],
        'sql' => 'text NOT NULL',
    ],
    'conditions' => [
        'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['conditions'],
        'default' => '',
        'search' => true,
        'inputType' => 'conditionwizard',
        'eval' => [],
        'sql' => "varchar(1) NOT NULL default ''",
    ],
    'page_template' => [
        'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['page_template'],
        'default' => 'survey_questionblock',
        'inputType' => 'select',
        'options_callback' => static function () {
            return Controller::getTemplateGroup('survey_');
        },
        'eval' => ['tl_class' => 'w50'],
        'sql' => "varchar(255) NOT NULL default 'survey_questionblock'",
    ],
    'pagetype' => [
        'sql' => "varchar(30) NOT NULL default 'standard'",
    ],
];
