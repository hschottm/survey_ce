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

use Contao\MemberGroupModel as ContaoMemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;

class MemberGroupModel extends ContaoMemberGroupModel
{
    public function __construct($objResult = null)
    {
        parent::__construct($objResult);
    }

    /**
     * Find all members of the current group.
     */
    public function findAllMembers()
    {
        // get all members within groups
        $members = MemberModel::findBy(['groups IS NOT NULL', 'disable = ?', 'locked = ?'], ['', '']);

        if ($members) {
            $groupMembers = array_filter($members->getModels(), function ($member) {
                $arrGroups = StringUtil::deserialize($member->groups);
                if (\in_array((string)$this->id, $arrGroups, true)) {
                    return $member;
                }
            });
        } else {
            $groupMembers = null;
        }

        return $groupMembers;
    }
}
