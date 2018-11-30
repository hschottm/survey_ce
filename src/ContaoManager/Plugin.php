<?php

declare(strict_types=1);

namespace Hschottm\SurveyBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Hschottm\SurveyBundle\HschottmSurveyBundle;

/**
 * Plugin for the Contao Manager.
 *
 * @author Helmut Schottmüller (hschottm)
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
             BundleConfig::create(HschottmSurveyBundle::class)
              ->setLoadAfter([ContaoCoreBundle::class])
              ->setReplace(['survey']),
         ];
    }
}
