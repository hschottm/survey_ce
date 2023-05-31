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
use Contao\DC_Table;
use Contao\Input;
use Contao\StringUtil;
use Hschottm\SurveyBundle\SurveyResultModel;

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
    'list' => [
        'sorting' => [
            'mode' => 4,
            'filter' => true,
            'fields' => ['sorting'],
            'panelLayout' => 'search,filter,limit',
            'headerFields' => ['title', 'tstamp', 'description'],
            'child_record_callback' => ['\Hschottm\SurveyBundle\SurveyPagePreview', 'compilePreview'],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['edit'],
                'href' => 'table=tl_survey_question',
                'icon' => 'edit.svg',
            ],
            'editheader' => [
                'href' => 'act=edit',
                'icon' => 'header.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['copy'],
                'href' => 'act=paste&mode=copy',
                'icon' => 'copy.svg',
                'button_callback' => ['tl_survey_page', 'copyPage'],
            ],
            'cut' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['cut'],
                'href' => 'act=paste&mode=cut',
                'icon' => 'cut.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
                'button_callback' => ['tl_survey_page', 'cutPage'],
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
                'button_callback' => ['tl_survey_page', 'deletePage'],
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],
    'palettes' => [
        '__selector__' => ['type', 'useCustomNextButtonTitle'],
        'default' => '{type_legend},type;{title_legend},title,description;{intro_legend},introduction;{template_legend},page_template',
        'result' => '{type_legend},type;{title_legend},title,description;{intro_legend},introduction;{config_legend},useCustomNextButtonTitle,markSurveyAsFinished;{template_legend},page_template',
    ],
    'subpalettes' => [
        'useCustomNextButtonTitle' => 'customNextButtonTitle',
    ],
    'fields' => [
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
        'type' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => ['default', 'result'],
            'reference' => &$GLOBALS['TL_LANG']['tl_survey_page']['type'],
            'eval' => ['submitOnChange' => true, 'tl_class' => 'w50'],
            'sql' => ['name' => 'type', 'type' => 'string', 'length' => 8, 'default' => 'default'],
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_page']['title'],
            'search' => true,
            'sorting' => true,
            'filter' => true,
            'flag' => 1,
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
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default 'survey_questionblock'",
        ],
        'pagetype' => [
            'sql' => "varchar(30) NOT NULL default 'standard'",
        ],
        'useCustomNextButtonTitle' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'customNextButtonTitle' => [
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 128],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'hideBackButton' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'markSurveyAsFinished' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "char(1) NOT NULL default ''",
        ]
    ],
];

/**
 * Class tl_survey_page.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
class tl_survey_page extends Backend
{
    protected $hasData;

    /**
     * Return the copy page button.
     *
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param mixed $row
     * @param mixed $href
     * @param mixed $label
     * @param mixed $title
     * @param mixed $icon
     * @param mixed $attributes
     *
     * @return string
     */
    public function copyPage($row, $href, $label, $title, $icon, $attributes)
    {
        if ($this->hasData()) {
            return $this->generateImage(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
        }

        return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }

    /**
     * Return the cut page button.
     *
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param mixed $row
     * @param mixed $href
     * @param mixed $label
     * @param mixed $title
     * @param mixed $icon
     * @param mixed $attributes
     *
     * @return string
     */
    public function cutPage($row, $href, $label, $title, $icon, $attributes)
    {
        if ($this->hasData()) {
            return $this->generateImage(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
        }

        return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }

    /**
     * Return the delete page button.
     *
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param mixed $row
     * @param mixed $href
     * @param mixed $label
     * @param mixed $title
     * @param mixed $icon
     * @param mixed $attributes
     *
     * @return string
     */
    public function deletePage($row, $href, $label, $title, $icon, $attributes)
    {
        if ($this->hasData()) {
            return $this->generateImage(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
        }

        return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }

    protected function hasData()
    {
        if (null === $this->hasData) {
            $resultModel = SurveyResultModel::findBy(['pid=?'], [Input::get('id')]);
            $this->hasData = null !== $resultModel && $resultModel->count() > 0;
        }

        return $this->hasData;
    }
}
