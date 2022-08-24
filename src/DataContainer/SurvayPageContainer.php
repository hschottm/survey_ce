<?php

namespace Hschottm\SurveyBundle\DataContainer;

use Contao\Controller;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Hschottm\SurveyBundle\SurveyPageModel;
use Symfony\Component\HttpFoundation\RequestStack;

class SurvayPageContainer
{
    public const TABLE = 'tl_survey_page';

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Callback(table=SurvayPageContainer::TABLE, target="fields.page_template.options")
     */
    public function onPageTemplateCallback(DataContainer $dc = null): array
    {
        $templateGroup = 'survey_';
        $defaultTemplate = 'survey_questionblock';

        if (null !== $dc && $dc->id || 'edit' === $this->requestStack->getCurrentRequest()->query->get('act')) {
            $surveyPageModel = SurveyPageModel::findById($dc->id);

            if (null !== $surveyPageModel && 'result' === $surveyPageModel->type) {
                $templateGroup = 'surveypage_result_';
                $defaultTemplate = 'surveypage_result_default';
            }
        }

        $templates = Controller::getTemplateGroup($templateGroup, [], $defaultTemplate);

        if ('survey_' === $templateGroup) {
            $templates = array_filter($templates, function ($template) {
                if (!is_string($template) || str_starts_with($template, 'survey_answers_') || str_starts_with($template, 'survey_question_')) {
                    return false;
                }
                return true;
            });
        }

        return $templates;
    }
}