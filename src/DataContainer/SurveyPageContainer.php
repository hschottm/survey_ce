<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */

namespace Hschottm\SurveyBundle\DataContainer;

use Contao\Backend;
use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyPageModel;
use Hschottm\SurveyBundle\SurveyResultModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class SurveyPageContainer
{
    public const TABLE = 'tl_survey_page';

    public const PAGETYPE_RESULT = 'result';

    private $requestStack;
    private $security;
    private $hasData;

    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    /**
     * @Callback(table=SurveyPageContainer::TABLE, target="config.onload")
     */
    public function onLoadCallback(DataContainer $dc = null): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$dc || !$request) {
            return;
        }

        $resultData = ($id = $request->query->get('id', false)) ? (is_numeric($id) ? SurveyResultModel::findByPid((int) $id) : null) : null;

        if ($resultData) {
            $dca = &$GLOBALS['TL_DCA']['tl_survey_page'];
            $dca['config']['notEditable'] = true;
            $dca['config']['closed'] = true;
        }

        $this->adjustResultPageDca($dc, $request);
    }

    /**
     * @Callback(table=SurveyPageContainer::TABLE, target="fields.page_template.options")
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
            $templates = array_filter(
                $templates,
                static function ($template) {
                    if (!\is_string($template) || str_starts_with($template, 'survey_answers_') || str_starts_with($template, 'survey_question_')) {
                        return false;
                    }

                    return true;
                }
            );
        }

        return $templates;
    }

    /**
     * @Callback(table=SurveyPageContainer::TABLE, target="list.sorting.child_record")
     */
    public function onChildRecordCallback(array $row): string
    {
        $surveyPageCollection = SurveyPageModel::findBy(['pid=?', 'sorting<?'], [$row['pid'], $row['sorting']]);
        $position = null !== $surveyPageCollection ? $surveyPageCollection->count() + 1 : 1;

        $template = new FrontendTemplate('be_survey_page_preview');
        $template->page = $GLOBALS['TL_LANG']['tl_survey_page']['page'];
        $template->position = $position;
        $template->title = StringUtil::specialchars($row['title']);
        $template->description = StringUtil::specialchars($row['description']);
        $icon = ('result' === $row['type'] ? 'bundles/hschottmsurvey/images/page_result.svg' : 'bundles/hschottmsurvey/images/page_question.svg');
        $template->icon = Image::getHtml($icon, '', 'title="'.($GLOBALS['TL_LANG']['tl_survey_page']['type'][$row['type']] ?? '').'"');

        return $template->parse();
    }

    /**
     * @Callback(table=SurveyPageContainer::TABLE, target="list.operations.edit.button")
     */
    public function onListEditButtonCallback(array $arrRow, ?string $href, string $label, string $title, ?string $icon, string $attributes): string
    {
        if ('result' === ($arrRow['type'] ?? '')) {
            return '';
        }

        $id = $this->requestStack->getCurrentRequest()->query->get('id');

        if (!$id || $this->hasData((int) $id)) {
            return Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
        }

        $href = Backend::addToUrl($href.'&amp;id='.$arrRow['id'].(Input::get('nb') ? '&amp;nc=1' : ''));

        return '<a href="'.$href.'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }

    /**
     * @Callback(table=SurveyPageContainer::TABLE, target="list.operations.editheader.button")
     */
    public function onListEditHeaderButtonCallback(array $arrRow, ?string $href, string $label, string $title, ?string $icon, string $attributes): string
    {
        $id = $this->requestStack->getCurrentRequest()->query->get('id');

        if (!$id || $this->hasData((int) $id) || !$this->security->getUser()->canEditFieldsOf(static::TABLE)) {
            return Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
        }

        $href = Backend::addToUrl($href.'&amp;id='.$arrRow['id']);

        return '<a href="'.$href.'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }

    protected function hasData(int $id): bool
    {
        if (null === $this->hasData) {
            $this->hasData = null !== SurveyResultModel::findByPid($id);
        }

        return $this->hasData;
    }

    /**
     * @param DataContainer|null $dc
     */
    private function adjustResultPageDca(DataContainer $dc, Request $request): void
    {
        if (!$dc->id || 'edit' !== $request->query->get('act')) {
            return;
        }

        $surveyPageModel = SurveyPageModel::findByPk($dc->id);

        if (!$surveyPageModel || static::PAGETYPE_RESULT !== $surveyPageModel->type) {
            return;
        }

        $surveyModel = SurveyModel::findById($surveyPageModel->pid);

        if (!$surveyModel || !$surveyModel->allowback) {
            return;
        }

        PaletteManipulator::create()
            ->addField('hideBackButton', 'config_legend', PaletteManipulator::POSITION_APPEND)
            ->applyToPalette(static::PAGETYPE_RESULT, static::TABLE)
        ;
    }
}
