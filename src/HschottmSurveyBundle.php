<?php

declare(strict_types=1);

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
