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

namespace Hschottm\SurveyBundle\Tests;

use Hschottm\SurveyBundle\HschottmSurveyBundle;
use PHPUnit\Framework\TestCase;

class HschottmSurveyBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new HschottmSurveyBundle();

        $this->assertInstanceOf('Hschottm\SurveyBundle\HschottmSurveyBundle', $bundle);
    }
}
