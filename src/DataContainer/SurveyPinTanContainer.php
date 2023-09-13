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
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyPINTAN;
use Symfony\Component\HttpFoundation\RequestStack;

class SurveyPinTanContainer
{
    /**
     * @noinspection PhpPropertyOnlyWrittenInspection
     */
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Callback(table="tl_survey_pin_tan", target="config.onload")
     *
     * onLoadCallback
     *   - check the access method = survey type
     *   - suppress buttons etc.
     */
    public function onLoadCheckSurveyType(DataContainer $dc): void
    {
        if ($dc->id) {
            // we have a valid survey - get the survey data record
            $survey = SurveyModel::findByPk($dc->id);

            if ($survey) {
                // a survey is available - test access mode
                switch ($survey->access) {
                    case 'anon': // simple anonymous survey
                        unset(
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['createtan'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['exporttan'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['invite'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['remind']
                        );
                        break;

                    case 'anoncode': // anonymous or personalized survey with TANs
                        if ('1' !== $survey->limit_groups) {
                            unset(
                                $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['invite'],
                                $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['remind']
                            );
                        }
                        break;

                    case 'nonanoncode': // personalized survey with login required
                        break;

                    default:
                }
            }
        }
        // we don't have a survey
    }

    /**
     * @Callback(table="tl_survey_pin_tan", target="list.global_operations.invite.button")
     */
    public function inviteButton(
        ?string $href,
        string $label,
        string $title,
        string $class,
        string $attributes,
        string $table,
        ?array $rootIds
    ): string
    {
        if ($GLOBALS['TL_SVY']['nc_is_installed']) {
            /** @noinspection HtmlUnknownTarget */
            $html = sprintf('<a class="%s" href="%s" title="%s"%s>%s</a>',$class,Backend::addToUrl($href),StringUtil::specialchars($title),$attributes,$label);
        } else {
            /** @noinspection HtmlUnknownTarget */
            $html = sprintf('<span class="%s" title="%s"%s>%s</span>',"{$class}_disabled",StringUtil::specialchars($title),$attributes,$label);
        }
        return $html;
    }

    /**
     * @Callback(table="tl_survey_pin_tan", target="list.global_operations.remind.button")
     */
    public function remindButton(
        ?string $href,
        string $label,
        string $title,
        string $class,
        string $attributes,
        string $table,
        ?array $rootIds
    ): string
    {
        if ($GLOBALS['TL_SVY']['nc_is_installed']) {
            /** @noinspection HtmlUnknownTarget */
            $html = sprintf('<a class="%s" href="%s" title="%s"%s>%s</a>',$class,Backend::addToUrl($href),StringUtil::specialchars($title),$attributes,$label);
        } else {
            /** @noinspection HtmlUnknownTarget */
            $html = sprintf('<span class="%s" title="%s"%s>%s</span>',"{$class}_disabled",StringUtil::specialchars($title),$attributes,$label);
        }
        return $html;
    }


    /**
     * @Callback(table="tl_survey_pin_tan", target="list.label.label")
     */
    public function getLabel($row, $label)
    {
        $L = $GLOBALS['TL_LANG']['tl_survey_pin_tan'];
        // used icon
        $key = 0 === (int)$row['used'] ? 'tan_new' : 'tan_used';
        $alt = $L[$key];
        $attributes = "title='{$L[$key]}'";
        $usedIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);

        // generated
        $key = 'key';
        $alt = $L[$key];
        $generatedAt = date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['tstamp']);
        $attributes = "title='{$L[$key]} $generatedAt'";
        $generatedIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $generated = "$generatedIcon <span $attributes>$generatedAt</span>";

        $key = 'invite';
        $alt = $L['invited'];
        $invitedAt = 0 === (int) $row['invited'] ? $L['not_yet'] : date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['invited']);
        $attributes = "title='{$L['invited']} $invitedAt'";
        $inviteIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $invited = "$inviteIcon <span $attributes>$invitedAt</span>";

        $key = 'remind';
        $alt = $L['reminded'];
        $remindedAt = 0 === (int) $row['reminded'] ?
            $L['not_yet'] :
            $row['reminded_count'].$L['reminder'].date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['reminded']);
        $attributes = "title='{$L['reminded']} $remindedAt'";
        $remindIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $reminded = "$remindIcon <span $attributes>$remindedAt</span>";

        $member = 0 !== (int) $row['member_id'] ? ' &#10132; '.SurveyPINTAN::formatMember($row['member_id']) : '';

        $label = '0' === $row['member_id'] ?
            sprintf('<div>%s <strong>%s</strong> %s</div>', $usedIcon, $row['tan'], $generated, ) :
            sprintf("<div>%s <strong>%s</strong> %s $member %s %s</div>", $usedIcon, $row['tan'], $generated, $invited, $reminded);

        return $label;
    }
}
