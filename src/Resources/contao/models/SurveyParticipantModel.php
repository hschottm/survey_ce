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

/**
 * @property int $id
 * @property int $pid
 * @property int $tstamp
 * @property string $pin
 * @property int $category
 *
 * @method static static|null findByPin($val, array $opt=array())
 */
class SurveyParticipantModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_survey_participant';

    public function getCategory(): ?int
    {
        if (is_numeric($this->category)) {
            return (int) $this->category;
        }

        $surveyModel = SurveyModel::findByPk($this->pid);

        if (!$surveyModel || !$surveyModel->useResultCategories) {
            return null;
        }


        $currentUserResults = SurveyResultModel::findBy(
            ['pid=?', 'pin=?'],
            [$this->pid, $this->pin]
        );

        if (!$currentUserResults) {
            return null;
        }

        $categories = [];
        while ($currentUserResults->next()) {
            $answers = $currentUserResults->result;
        }

        // Get results
        // Calculate max category calue

        return null;
    }
}

class_alias(SurveyParticipantModel::class, 'SurveyParticipantModel');
