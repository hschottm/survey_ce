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
use Contao\Database;

class SurveyResultModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey_result';

    /**
     * Find multiple survey results by their IDs.
     *
     * @param array $arrIds An array of IDs
     *
     * @return \Model\Collection|null A collection of models or null if there are no calendars
     */
    public static function findMultipleByIds($arrIds, array $arrOptions = [])
    {
        if (!\is_array($arrIds) || empty($arrIds)) {
            return null;
        }

        $t = static::$strTable;

        return static::findBy(["$t.id IN(".implode(',', array_map('intval', $arrIds)).')'], null, ['order' => Database::getInstance()->findInSet("$t.id", $arrIds)]);
    }
}

class_alias(SurveyResultModel::class, 'SurveyResultModel');
