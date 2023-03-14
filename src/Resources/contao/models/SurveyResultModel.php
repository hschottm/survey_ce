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

namespace Hschottm\SurveyBundle;

use Contao\Database;
use Contao\Model;
use Contao\Model\Collection;

/**
 * @property int    $id
 * @property int    $tstamp
 * @property int    $pid
 * @property string $pin
 * @property int    $uid
 * @property int    $qid
 * @property mixed  $result
 *
 * @method static array<static>|Collection|null findBy($val, array $opt = [])
 * @method static array<static>|Collection|null findByPid($val, array $opt = [])
 */
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
