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

use Contao\Model;

/**
 * @property int    $id
 * @property int    $pid
 * @property int    $tstamp
 * @property string $pin
 * @property int    $category
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
}

class_alias(SurveyParticipantModel::class, 'SurveyParticipantModel');
