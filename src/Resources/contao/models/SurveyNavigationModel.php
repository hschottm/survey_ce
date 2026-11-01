<?php

/*
 * @copyright  Helmut Schottmüller 2005-2019 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0-or-later
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\Model;

class SurveyNavigationModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey_navigation';
}

class_alias(SurveyNavigationModel::class, 'SurveyNavigationModel');
