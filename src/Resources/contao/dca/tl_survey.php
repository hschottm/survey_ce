<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$found = (\strlen(\Input::get('id'))) ? \Hschottm\SurveyBundle\SurveyResultModel::findByPid(\Input::get('id')) : null;
$hasData = (null !== $found && 0 < $found->numRows) ? true : false;

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
        '__selector__' => ['access', 'limit_groups'],
        'default' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end',
        'anon' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{misc_legend},allowback,immediate_start,jumpto',
        'anoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{misc_legend},allowback,immediate_start,jumpto',
        'nonanoncode' => '{title_legend},title,author,description,language;{activation_legend},online_start,online_end;{access_legend},access,usecookie,limit_groups;{texts_legend},introduction,finalsubmission;{head_legend},show_title,show_cancel;{misc_legend},allowback,immediate_start,jumpto',
    ],

    // Palettes
    'subpalettes' => [
        'limit_groups' => 'allowed_groups',
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
    ],
];

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
}
