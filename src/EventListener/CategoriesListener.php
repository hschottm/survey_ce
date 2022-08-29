<?php

namespace Hschottm\SurveyBundle\EventListener;

use Contao\Backend;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\DataContainer;
use Contao\StringUtil;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyPageModel;
use Hschottm\SurveyBundle\SurveyQuestionModel;
use Mvo\ContaoGroupWidget\MvoContaoGroupWidgetBundle;
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
     * @Hook("loadDataContainer")
     */
    public function onLoadDataContainer(string $table): void
    {
//        if (SurveyModel::getTable() === $table && $this->preconditionsMet()) {
//            $dca = $GLOBALS['TL_DCA'][$table];
//
//            $dca['subpalettes']['useResultCategories'] = 'resultCategories';
//
//            PaletteManipulator::create()
//                ->addField('useResultCategories', 'misc_legend', PaletteManipulator::POSITION_APPEND)
//                ->applyToPalette('default', $table)
//                ->applyToPalette('anon', $table)
//                ->applyToPalette('anoncode', $table)
//                ->applyToPalette('nonanoncode', $table)
//            ;
//
//            $dca['fields']['useResultCategories'] = [
//                'exclude'   => true,
//                'inputType' => 'checkbox',
//                'eval'      => ['tl_class' => 'w50', 'submitOnChange' => true],
//                'sql'       => "char(1) NOT NULL default ''",
//            ];
//
//            $dca['fields']['resultCategories'] = [
//                'exclude'   => true,
//                'inputType' => 'group',
//                'palette' => ['category'],
//                'fields' => [
//                    'category' => [
//                        'inputType' => 'text',
//                    ],
//                ],
//                'sql' => [
//                    'type' => 'blob',
//                    'length' => MySqlPlatform::LENGTH_LIMIT_BLOB,
//                    'notnull' => false,
//                ],
//            ];
//        }
    }

    /**
     * @Callback(table="tl_survey_question", target="config.onload")
     */
    public function onLoadCallback(DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== $this->requestStack->getCurrentRequest()->query->get('act')) {
            return;
        }

        $questionModel = SurveyQuestionModel::findByPk($dc->id);
        if ('multiplechoice' === $questionModel->questiontype && 'mc_singleresponse' === $questionModel->multiplechoice_subtype) {
            $GLOBALS['TL_LANG']['tl_survey_question']['choices'][1] =
                ($GLOBALS['TL_LANG']['tl_survey_question']['choices'][1] ?? '')
                .'</p>'
                .'<a class="tl_submit" style="margin-top: 10px;" href="'.Backend::addToUrl('key=scale').'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][1]).'" onclick="Backend.getScrollOffset();">'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][0]).'</a><p style="height: 0;margin: 0;">';
        }
    }
}