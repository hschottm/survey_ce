<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\Input;
use Symfony\Contracts\Translation\TranslatorInterface;
use Hschottm\SurveyBundle\SurveyPageModel;
use Doctrine\DBAL\Connection;

class SurveyPageCallbackListener
{
    public function __construct(
        private readonly TranslatorInterface $translator, 
        private readonly Connection $db,
    )
    {
    }
    
    #[AsCallback(table: 'tl_survey_page', target: 'list.label.label')]
    public function formatColumnView(array $row, string $label, DataContainer $dc): string
    {
        $surveyPageCollection = SurveyPageModel::findBy(['pid=?', 'sorting<?'], [$row['pid'], $row['sorting']]);
        $position = (null != $surveyPageCollection) ? $surveyPageCollection->count() + 1 : 1;

        $template = new FrontendTemplate('be_survey_page_preview');
        $template->page = $GLOBALS['TL_LANG']['tl_survey_page']['page'];
        $template->position = $position;
        $template->title = StringUtil::specialchars($row['title']);
        $template->description = StringUtil::specialchars($row['description']);

        return $template->parse();
    }

    #[AsCallback(table: 'tl_survey_page', target: 'list.sorting.child_record')]
    public function surveyQuestionSortChildRecord(array $recordData): string
    {
        $surveyPageCollection = SurveyPageModel::findBy(['pid=?', 'sorting<?'], [$recordData['pid'], $recordData['sorting']]);
        $position = (null != $surveyPageCollection) ? $surveyPageCollection->count() + 1 : 1;

        $template = new FrontendTemplate('be_survey_page_preview');
        $template->page = $GLOBALS['TL_LANG']['tl_survey_page']['page'];
        $template->position = $position;
        $template->title = StringUtil::specialchars($recordData['title']);
        $template->description = StringUtil::specialchars($recordData['description']);

        return $template->parse();
    }
}
