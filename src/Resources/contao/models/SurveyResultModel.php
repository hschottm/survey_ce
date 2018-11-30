<?php

namespace Hschottm\SurveyBundle;

/**
 * Reads and writes calendars
 *
 * @package   Models
 * @author    Helmut Schottmüller <https://github.com/hschottm>
 * @copyright Helmut Schottmüller 2012
 */
class SurveyResultModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_survey_result';


	/**
	 * Find multiple survey results by their IDs
	 *
	 * @param array $arrIds An array of IDs
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no calendars
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, array('order'=>\Database::getInstance()->findInSet("$t.id", $arrIds)));
	}
}
