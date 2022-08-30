<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\Model;
use Contao\Model\Collection;

/**
 * @property int $id
 * @property int $pid
 * @property string $type
 *
 * @method static Collection|SurveyPageModel[]|SurveyPageModel|null findByType($val, array $opt=array())
 */
class SurveyPageModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey_page';
}

class_alias(SurveyPageModel::class, 'SurveyPageModel');
