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

use Contao\Backend;
use Hschottm\SurveyBundle\SurveyModel;
use Hschottm\SurveyBundle\SurveyPINTAN;

$GLOBALS['TL_DCA']['tl_survey_pin_tan'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_survey',
        'doNotCopyRecords' => true,
        'closed' => true,
        'enableVersioning' => true,
        'onload_callback' => [
            ['tl_survey_pin_tan', 'onLoadCheckSurveyType'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
                'pin' => 'index',
                'tan' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['tan'],
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['tan', 'tstamp', 'used'],
            'format' => '%s::%s::%s',
            'label_callback' => ['tl_survey_pin_tan', 'getLabel'],
        ],
        'global_operations' => [
            'createtan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'],
                'href' => 'key=createtan',
                'icon' => 'bundles/hschottmsurvey/images/key.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'exporttan' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'],
                'href' => 'key=exporttan',
                'class' => 'header_export',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'invite' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['invite'],
                'href' => 'key=invite',
                'class' => 'header_invite',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'remind' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['remind'],
                'href' => 'key=remind',
                'class' => 'header_remind',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes' => [],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        // 0 means: the TAN is valid for all member
        'member_id' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pin' => [
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'tan' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'],
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'insertTag' => true],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'used' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'],
            'filter' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sorting' => true,
            'flag' => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'insertTag' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'invited' => [
            'sorting' => true,
            'flag' => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'reminded' => [
            'sorting' => true,
            'flag' => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'reminded_count' => [
            'sorting' => true,
            'flag' => 6, // desc, grouped by day (side effect: tstamp label is now in 'datimFormat')
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'maxlength' => 16],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];

/**
 * Class tl_survey_pin_tan.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
class tl_survey_pin_tan extends Backend
{
    public function getLabel($row, $label)
    {
        $L = $GLOBALS['TL_LANG']['tl_survey_pin_tan'];
        // used icon
        $key = $row['used'] === '0' ? 'tan_new' : 'tan_used';
        $alt = $L[$key];
        $attributes = "title='{$L[$key]}'";
        $usedIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);

        // generated
        $key = 'key';
        $alt = $L[$key];
        $generatedAt   = date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['tstamp']);
        $attributes = "title='{$L[$key]} $generatedAt'";
        $generatedIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $generated     = "$generatedIcon <span $attributes>$generatedAt</span>";

        $key = 'invite';
        $alt = $L['invited'];
        $invitedAt  = (int) $row['invited'] === 0 ? $L['not_yet'] : date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['invited']);
        $attributes = "title='{$L['invited']} $invitedAt'";
        $inviteIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $invited    = "$inviteIcon <span $attributes>$invitedAt</span>";

        $key = 'remind';
        $alt = $L['reminded'];
        $remindedAt  = (int) $row['reminded'] === 0 ?
            $L['not_yet'] :
            $row['reminded_count'] . $L['reminder'] . date($GLOBALS['TL_CONFIG']['datimFormat'], (int) $row['reminded']);
        $attributes = "title='{$L['reminded']} $remindedAt'";
        $remindIcon = Image::getHtml("bundles/hschottmsurvey/images/$key.svg", $alt, $attributes);
        $reminded    = "$remindIcon <span $attributes>$remindedAt</span>";

        $member = 0 !== (int) $row['member_id'] ? ' &#10132; '.SurveyPINTAN::formatMember($row['member_id']) : '';

        return sprintf(
            "<div>%s <strong>%s</strong> %s $member %s %s</div>",
            $usedIcon,
            $row['tan'],
            $generated,
            $invited,
            $reminded
        );
    }

    /**
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
                    case 'anon':
                        unset(
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['createtan'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['exporttan'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['invite'],
                            $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['remind']
                        );
                        break;
                    case 'anoncode':
                        if($survey->useNotifications !== '1') {
                            unset(
                                $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['invite'],
                                $GLOBALS['TL_DCA'][$dc->table]['list']['global_operations']['remind']
                            );
                        }
                        break;
                    case 'nonanoncode':
                        break;

                    default:
                }
            }
        }
        // we don't have a survey
    }
}
