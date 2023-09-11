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
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Hschottm\SurveyBundle\SurveyResultModel;
use Symfony\Component\HttpFoundation\RequestStack;

class SurveyContainer
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Callback(table="tl_survey", target="config.onload")
     */
    public function onLoadCallback(DataContainer $dc = null): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $dc || !$dc->id || !$request || 'edit' !== $request->query->get('act')) {
            return;
        }
        // look for a valid result
        $resultData = ($id = $request->query->get('id', false)) ? (is_numeric($id) ? SurveyResultModel::findByPid((int) $id) : null) : null;
        // when we have a result, we lock the access configurations
        if ($resultData) {
            $GLOBALS['TL_DCA']['tl_survey']['fields']['access']['eval']['disabled'] = 'disabled';
            $GLOBALS['TL_DCA']['tl_survey']['fields']['limit_groups']['eval']['disabled'] = 'disabled';
            $GLOBALS['TL_DCA']['tl_survey']['fields']['allowed_groups']['eval']['disabled'] = 'disabled';
            $GLOBALS['TL_DCA']['tl_survey']['fields']['useNotifications']['eval']['disabled'] = 'disabled';
            $GLOBALS['TL_DCA']['tl_survey']['fields']['surveyPage']['eval']['disabled'] = 'disabled';
        }
    }

    /**
     * @Callback(table="tl_survey", target="list.operations.pintan.button")
     */
    public function pintanButton(array $row, ?string $href, string $label, string $title, ?string $icon, string $attributes, string $table, ?array $rootRecordIds, ?array $childRecordIds, bool $circularReference, ?string $previous, ?string $next, DataContainer $dc)
    {
        // anon does not have a key list
        if ('anon' === $row['access']) {
            return '';
        }

        /** @noinspection HtmlUnknownTarget */
        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href.'&amp;id='.$row['id']),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
        );
    }

    /**
     * @Callback(table="tl_survey", target="list.label.label")
     *
     * Add an image to each record.
     *
     * @param array
     * @param string
     * @param mixed $row
     * @param mixed $label
     *
     * @return string
     */
    public function addIcon($row, $label)
    {
        return sprintf('<div class="list_icon" style="background-image:url(\'bundles/hschottmsurvey/images/survey-outline.svg\');">%s</div>', $label);
    }

    /**
     * @Callback(table="tl_survey", target="fields.confirmationMailRecipientField.options")
     */
    public function getEmailFormFields()
    {
        $fields = [];

        // Get all form fields which can be used to define recipient of confirmation mail
        $objFields = Database::getInstance()->prepare('SELECT tl_survey_question.id,tl_survey_question.title FROM tl_survey_question, tl_survey_page WHERE tl_survey_question.pid = tl_survey_page.id AND tl_survey_page.pid = ? AND tl_survey_question.questiontype=? ORDER BY tl_survey_question.title ASC')->execute(Input::get('id'), 'openended');

        $fields[] = '-';

        while ($objFields->next()) {
            $k = $objFields->id;

            if (\strlen($k)) {
                $v = $objFields->title;
                $v = \strlen($v) ? $v.' ['.$k.']' : $k;
                $fields[$k] = $v;
            }
        }

        return $fields;
    }
}
