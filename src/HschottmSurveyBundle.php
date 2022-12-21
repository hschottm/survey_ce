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

use Hschottm\SurveyBundle\DependencyInjection\SurveyExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HschottmSurveyBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SurveyExtension();
    }
}
