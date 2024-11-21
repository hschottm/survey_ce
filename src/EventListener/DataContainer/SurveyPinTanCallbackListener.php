<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\Input;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\DBAL\Connection;

class SurveyPinTanCallbackListener
{
    public function __construct(
        private readonly TranslatorInterface $translator, 
        private readonly Connection $db,
    )
    {
    }
    
    #[AsCallback(table: 'tl_survey_pin_tan', target: 'list.label.label')]
    public function formatListLabel(array $row, string $label, DataContainer $dc): string
    {
        preg_match('/^(.*?)::(.*?)::(.*?)$/', $label, $matches);
        if ($matches[3]) {
            // tan is used
            $used = '<img src="bundles/hschottmsurvey/images/tan_used.png" alt="'.$this->translator->trans('tl_survey_pin_tan.tan_used').'" title="'.$this->translator->trans('tl_survey_pin_tan.tan_used').'" />';
        } else {
            $used = '<img src="bundles/hschottmsurvey/images/tan_new.png" alt="'.$this->translator->trans('tl_survey_pin_tan.tan_new').'" title="'.$this->translator->trans('tl_survey_pin_tan.tan_new').'" />';
        }

        return sprintf('<div>%s <strong>%s</strong> (%s)</div>', $used, $matches[1], $matches[2]);
    }
}
