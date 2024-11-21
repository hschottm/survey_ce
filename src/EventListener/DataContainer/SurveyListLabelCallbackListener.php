<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCallback(table: 'tl_survey', target: 'list.label.label')]
class SurveyListLabelCallbackListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function __invoke(array $row, string $label, DataContainer $dc, array $labels): string
    {
        return sprintf('<div class="list_icon" style="background-image:url(\'bundles/hschottmsurvey/images/survey-outline.svg\');">%s</div>', $label);
    }
}
