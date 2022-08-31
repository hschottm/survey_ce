<?php

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

$date = date('Y');

$GLOBALS['ecsHeader'] = <<<EOF
@copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
@author     Helmut Schottmüller (hschottm)
@package    contao-survey
@license    LGPL-3.0+, CC-BY-NC-3.0
@see	       https://github.com/hschottm/survey_ce

forked by pdir
@author     Mathias Arzberger <develop@pdir.de>
@link       https://github.com/pdir/contao-survey
EOF;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(__DIR__.'/vendor/contao/easy-coding-standard/config/set/contao.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::LINE_ENDING, "\n");

    $services = $containerConfigurator->services();
    $services
        ->set(HeaderCommentFixer::class)
        ->call('configure', [[
            'header' => $GLOBALS['ecsHeader'],
        ]])
    ;
};
