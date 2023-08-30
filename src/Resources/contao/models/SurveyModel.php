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
use Contao\MemberModel;
use Contao\Model;
use Contao\StringUtil;

/**
 * @property bool   $useResultCategories
 * @property string $resultCategories
 */
class SurveyModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey';

    protected static $questionMapper = [];

    public static function findByQuestionId(int $questionId): ?self
    {
        if (isset(static::$questionMapper[$questionId])) {
            return static::findByPk(static::$questionMapper[$questionId]);
        }

        $result = Database::getInstance()->prepare(
            'SELECT tl_survey.id FROM tl_survey '
            .'JOIN tl_survey_page ON tl_survey_page.pid = tl_survey.id '
            .'JOIN tl_survey_question ON tl_survey_question.pid = tl_survey_page.id '
            .'WHERE tl_survey_question.id=?'
        )->execute($questionId);

        $surveyModel = static::findByPk($result->id ?? 0);

        if ($surveyModel) {
            static::$questionMapper[$questionId] = $surveyModel->id;
        }

        return $surveyModel;
    }

    public function getCategoryName(int $id): string
    {
        $categories = StringUtil::deserialize($this->resultCategories, true);
        $categories = array_column($categories, 'category', 'id');

        return $categories[$id] ?? '';
    }

    /**
     * retrieves all members of a survey and returns them as a unique collection
     * duplicate members are supressed
     *
     * @param $blnIncludeDisable    prepared for future use
     * @param $blnIncludeLocked     prepared for future use
     * @return array|null
     * @throws \Exception
     */
    public function findAllUniqueParticipants($blnIncludeDisable = false, $blnIncludeLocked = false): ?Model\Collection
    {
        $result = null;
        // decode groups
        $allowed_groups = StringUtil::deserialize($this->allowed_groups);
        // do we have valid groups?
        if($this->limit_groups === '1' && $allowed_groups) {
            // survey has valid groups
            // get all groups for this survey
            $memberGroups = $this->getRelated('allowed_groups');
            // iterate over each group
            foreach($memberGroups->getModels() as $memberGroup) {
                // only use enabled groups at this time
                if ($memberGroup->disable !== '1') {
                    // $members is NULL if the group is empty
                    if ($members = $memberGroup->findAllMembers())
                        foreach ($members as $member) { $result[$member->id] = $member; }
                }
            }
        } else {
            // survey has no group = return all members
            if($members = MemberModel::findBy(['disable = ?', 'locked = ?'], ['', '']))
                foreach ($members as $member) { $result[$member->id] = $member; }
        }

        return is_null($result) ? $result : new Model\Collection(array_values($result), self::$strTable);
    }
}

class_alias(SurveyModel::class, 'SurveyModel');
