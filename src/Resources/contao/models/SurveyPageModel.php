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
use Contao\Model\Collection;

/**
 * @property int    $id
 * @property int    $pid
 * @property string $type
 *
 * @method static Collection|array<SurveyPageModel>|SurveyPageModel|null findByType($val, array $opt = [])
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
