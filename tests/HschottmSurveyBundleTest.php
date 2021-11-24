<?php

declare(strict_types=1);

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
