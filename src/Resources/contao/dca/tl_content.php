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

use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_content']['palettes']['survey'] = '{type_legend},type,headline;{survey_legend},survey;{template_legend:hide},surveyTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['fields']['survey'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['survey'],
    'exclude' => true,
    'inputType' => 'radio',
    'foreignKey' => 'tl_survey.title',
    'eval' => ['mandatory' => true],
    'sql' => "smallint(5) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['surveyTpl'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['surveyTpl'],
    'default' => 'ce_survey',
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => ['tl_content_survey', 'getSurveyTemplates'],
    'sql' => "varchar(64) NOT NULL default ''",
];

class tl_content_survey extends tl_content
{
    /**
     * Return all survey templates as array.
     *
     * @param object $dc
     *
     * @return array
     */
    public function getSurveyTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('ce_survey');
    }
}
