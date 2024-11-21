<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\Input;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\DBAL\Connection;

class SurveyScaleCallbackListener
{
    public function __construct(
        private readonly TranslatorInterface $translator, 
        private readonly Connection $db,
    )
    {
    }
    
    #[AsCallback(table: 'tl_survey_scale', target: 'list.sorting.child_record')]
    public function sortChildRecord(array $recordData, DataContainer $dc): string
    {
        if (!$dc->id) {
            return "";
        }

        $result = '<p><strong>'.$recordData['title'].'</strong></p>';
        $result .= '<ol>';
        $answers = StringUtil::deserialize($recordData['scale'], true);
        foreach ($answers as $answer) {
            $result .= '<li>'.StringUtil::specialchars($answer).'</li>';
        }
        $result .= '</ol>';

        return $result;
    }
}
