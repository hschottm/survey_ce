<?php

namespace Hschottm\SurveyBundle\EventListener;

use Contao\Backend;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\StringUtil;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyPageModel;
use Hschottm\SurveyBundle\SurveyQuestionModel;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoriesListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Callback(table="tl_survey_question", target="config.onload")
     */
    public function adaptChoicesField(DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== $this->requestStack->getCurrentRequest()->query->get('act')) {
            return;
        }

        $questionModel = SurveyQuestionModel::findByPk($dc->id);

        if (!$questionModel || 'multiplechoice' !== $questionModel->questiontype) {
            return;
        }

        if ('mc_singleresponse' === $questionModel->multiplechoice_subtype) {
            $GLOBALS['TL_LANG']['tl_survey_question']['choices'][1] =
                ($GLOBALS['TL_LANG']['tl_survey_question']['choices'][1] ?? '')
                .'</p>'
                .'<a class="tl_submit" style="margin-top: 10px;" href="'.Backend::addToUrl('key=scale').'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][1]).'" onclick="Backend.getScrollOffset();">'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][0]).'</a><p style="height: 0;margin: 0;">';
        }

        if (($surveyPageModel = SurveyPageModel::findByPk($questionModel->pid))
            && ($surveyModel = SurveyModel::findByPk($surveyPageModel->pid))
            && ($surveyModel->useResultCategories)
        ) {
            $choicesField = &$GLOBALS['TL_DCA'][SurveyQuestionModel::getTable()]['fields']['choices'];
            $choicesField['palette'][] = 'category';
            $choicesField['fields']['category'] = [
                'inputType' => 'select',
                'options_callback' => [self::class, 'surveyChoicesCategoryOptionsCallback'],
            ];
        }
    }

    /**
     * @Callback(table="tl_survey", target="config.onload")
     */
    public function addCategoryIds(DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== $this->requestStack->getCurrentRequest()->query->get('act')) {
            return;
        }

        /** @var SurveyModel|null $survey */
        $survey = SurveyModel::findById($dc->id);

        if (null === $survey || !$survey->useResultCategories) {
            return;
        }

        $categories = StringUtil::deserialize($survey->resultCategories);
        $ids = array_filter(array_column($categories, "id"));
        $max = 0;
        if (!empty($ids)) {
            $max = (max($ids)+1);
        }

        foreach ($categories as $key => $category) {
            if (empty($category['id']) && 0 !== $category['id']) {
                $categories[$key]['id'] = $max;
                $max++;
            }
        }
        $survey->resultCategories = serialize($categories);
        $survey->save();
    }

    /**
     * @param DataContainer|null $dc
     * @return array
     */
    public function surveyChoicesCategoryOptionsCallback(DataContainer $dc = null): array
    {
        $options = [];
        if ($dc && $dc->id || 'edit' === $this->requestStack->getCurrentRequest()->query->get('act')) {
            if (!($questionModel = SurveyQuestionModel::findByPk($dc->id))
            || !($surveyPageModel = SurveyPageModel::findByPk($questionModel->pid))
            || !($surveyModel = SurveyModel::findByPk($surveyPageModel->pid))) {
                return $options;
            }
            $categories = StringUtil::deserialize($surveyModel->resultCategories, true);
            foreach ($categories as $category) {
                $options[$category['id']] = $category['category'];
            }
        }

        return $options;
    }
}