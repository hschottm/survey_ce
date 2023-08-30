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

namespace Hschottm\SurveyBundle;

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\DataContainer;
use Contao\Environment;
use Contao\Input;
use Contao\MemberModel;
use Contao\Message;
use Contao\PageModel;
use Contao\PageTree;
use Contao\SelectMenu;
use Contao\StringUtil;
use Contao\System;
use Contao\TextField;
use Contao\Widget;
use Hschottm\SurveyBundle\Export\Exporter;
use Hschottm\SurveyBundle\Export\ExportHelper;

use Symfony\Component\DomCrawler\Field\InputFormField;

/**
 * Class SurveyPINTAN.
 *
 * Provide methods to handle import and export of member data.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyPINTAN extends Backend
{
    protected $blnSave = true;

    public function exportTAN(DataContainer $dc): string
    {
        if ('exporttan' !== Input::get('key')) {
            return '';
        }

        if ('tl_survey_pin_tan' === Input::get('table')) {
            $this->redirect(Backend::addToUrl('table=tl_survey', true, ['table']));
        }

        $this->loadLanguageFile('tl_survey_pin_tan');
        $this->Template = new BackendTemplate('be_survey_export_tan');

        $this->Template->surveyPage = $this->getSurveyPageWidget();

        $this->Template->hrefBack = Backend::addToUrl('table=tl_survey_pin_tan', true, ['table', 'key']);
        $this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->headline = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'];
        $this->Template->request = StringUtil::ampersand(str_replace('&id=', '&pid=', Environment::get('request')));
        $this->Template->submit = StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_pin_tan']['export']);

        // Create import form
        if ('tl_export_survey_pin_tan' === Input::post('FORM_SUBMIT') && $this->blnSave) {
            $export = [];
            $surveyPage = $this->Template->surveyPage->value;
            $pageModel = PageModel::findOneBy('id', $surveyPage);
            $pagedata = null !== $pageModel ? $pageModel->row() : null;
            $domain = Environment::get('base');

            $res = SurveyPinTanModel::findBy('pid', Input::get('pid'), ['order' => 'tstamp DESC, id DESC']);

            foreach ($res as $objPINTAN) {
                $row = $objPINTAN->row();
                $line = [];
                $line['tan'] = $row['tan'];
                $line['tstamp'] = date($GLOBALS['TL_CONFIG']['datimFormat'], (int)$row['tstamp']);
                $line['used'] = $row['used'] ? 1 : 0;

                if((int)$row['member_id'] > 0) {
                    $line['member_id'] = trim(self::formatMember($row['member_id']));
                } else {
                    $line['member_id'] = 'alle';
                }

                if (null !== $pagedata) {
                    $line['url'] = ampersand($domain.$this->generateFrontendUrl($pagedata, '/code/'.$row['tan']));
                }
                $export[] = $line;
            }

            if (\count($export)) {
                $exporter = ExportHelper::getExporter();
                $sheet = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tans'];
                $exporter->addSheet($sheet);

                // Headers
                $intRowCounter = 0;
                $intColCounter = 0;

                $exporter->setCellValue(
                    $sheet,
                    $intRowCounter,
                    $intColCounter,
                    [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tan'][0],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                    ]
                );
                ++$intColCounter;

                $exporter->setCellValue(
                    $sheet,
                    $intRowCounter,
                    $intColCounter,
                    [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['tstamp'][0],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                        Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO,
                    ]
                );
                ++$intColCounter;

                $exporter->setCellValue(
                    $sheet,
                    $intRowCounter,
                    $intColCounter,
                    [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'][0],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                    ]
                );
                ++$intColCounter;

                $exporter->setCellValue(
                    $sheet,
                    $intRowCounter,
                    $intColCounter,
                    [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['member_id'][0],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                    ]
                );
                ++$intColCounter;

                if (null !== $pagedata) {
                    $exporter->setCellValue(
                        $sheet,
                        $intRowCounter,
                        $intColCounter,
                        [
                            Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['url'],
                            Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                            Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO,
                        ]
                    );
                    ++$intColCounter;
                }

                $exporter->setCellValue(
                    $sheet,
                    $intRowCounter,
                    $intColCounter,
                    [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['sort'],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                        Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO,
                    ]
                );

                // Data
                $intRowCounter = 1;

                foreach ($export as $line) {
                    $intColCounter = 0;

                    foreach ($line as $data) {
                        $celldata = [
                            Exporter::DATA => $data,
                        ];

                        if (0 === $intColCounter) {
                            $celldata[Exporter::CELLTYPE] = Exporter::CELLTYPE_STRING;
                        }
                        $exporter->setCellValue(
                            $sheet,
                            $intRowCounter,
                            $intColCounter,
                            $celldata
                        );
                        ++$intColCounter;
                    }
                    $exporter->setCellValue(
                        $sheet,
                        $intRowCounter,
                        $intColCounter,
                        [
                            Exporter::DATA => $intRowCounter,
                            Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT,
                        ]
                    );
                    ++$intRowCounter;
                }
                $surveyModel = SurveyModel::findOneBy('id', Input::get('pid'));

                if (null !== $surveyModel) {
                    $exporter->setFilename('TAN_'.$surveyModel->title);
                } else {
                    $exporter->setFilename('TAN');
                }
                $exporter->sendFile('TAN', 'TAN', 'TAN', 'Contao CMS', 'Contao CMS');
                exit;
            }
            $this->redirect(Backend::addToUrl('table=tl_survey_pin_tan', true, ['key', 'table']));
        }

        return $this->Template->parse();
    }

    /**
     * this function handles the createTAN action
     *
     * @param DataContainer $dc
     * @return string
     */
    public function createTAN(DataContainer $dc): string
    {
        if ('createtan' !== Input::get('key')) {
            return '';
        }

        $this->loadLanguageFile('tl_survey_pin_tan');
        $this->loadLanguageFile('tl_survey');

        // prepare template
        $this->Template = new BackendTemplate('be_survey_create_tan');

        $this->Template->hrefBack   = StringUtil::ampersand(str_replace('&key=createtan', '', Environment::get('request')));
        $this->Template->goBack     = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->headline   = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'][0];
        $this->Template->request    = StringUtil::ampersand(Environment::get('request'));
        $this->Template->submit     = StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_pin_tan']['create']);

        // handle GET request and render template

        // get the survey data record
        $survey= SurveyModel::findByPk($dc->id);
        $memberGroup = '';

        if($survey) {
            // a survey is available - test access mode
            switch($survey->access) {
                case 'anon':
                    // no TAN generation available here
                    $this->redirect(Backend::addToUrl('', true, ['key']));
                    break;
                case 'anoncode':
                    // generate anonymous TANs
                    $this->Template->nrOfTAN = $this->getTANWidget();
                    // handle a POST request
                    $this->handlePOST($survey);
                    break;
                case 'nonanoncode':
                    // generate member-related TANs
                    // check member groups
                    if($survey->limit_groups === '1' && $survey->allowed_groups) {
                        // specific groups
                        $groups = $survey->getRelated('allowed_groups');

                        foreach($groups->getModels() as $k => $group) {
                            $groupNames[] = $group->name;
                        }

                        $memberGroup = sprintf(
                            $GLOBALS['TL_LANG']['tl_survey']['access']['group'][1],
                            implode(', ', $groupNames)
                        );
                    } else {
                        // no groups = all members
                        $memberGroup = sprintf($GLOBALS['TL_LANG']['tl_survey']['access']['group'][0]);
                    }
                    // handle a POST request
                    $this->handlePOST($survey);
                    break;
                default:

            }

            $this->Template->note = sprintf(
                $GLOBALS['TL_LANG']['tl_survey']['access_template'],
                $GLOBALS['TL_LANG']['tl_survey']['access'][$survey->access][0],
                $GLOBALS['TL_LANG']['tl_survey']['access'][$survey->access][1],
                sprintf(
                    $GLOBALS['TL_LANG']['tl_survey']['access'][$survey->access][2],
                    $memberGroup
                ),
            );

        } else {
            // survey not found
            $this->redirect(Backend::addToUrl('', true, ['key','id','table']));
        }

        return $this->Template->parse();
    }

    /**
     * handles a POST request from the tl_survey_pin_tan
     *
     * @param string $access
     * @return void
     */
    private function handlePOST(SurveyModel $survey):void
    {
        // handle POST request and redirect
        if ('tl_generate_survey_pin_tan' === Input::post('FORM_SUBMIT') && $this->blnSave)
        {
            switch($survey->access) {
                case 'anon':
                    break;
                case 'anoncode':
                    // generate anonymous TANs
                    $nrOfTAN = abs((int) Input::post('nrOfTAN'));
                    $this->import('\Hschottm\SurveyBundle\Survey', 'svy');

                    $newCount = $exiCount = 0;
                    $exiCount = SurveyPinTanModel::countBy(['pid = ? AND used = 0', 'member_id = 0'],[$survey->id]);

                    if($exiCount <= $nrOfTAN) {
                        for ($i = 0; $i < ($nrOfTAN - $exiCount); ++$i) {
                            $pintan = $this->svy->generatePIN_TAN();
                            $this->insertPinTan($survey->id, $pintan['PIN'], $pintan['TAN'], 0);
                            $newCount++;
                        }
                    }
                    // show generator result
                    Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_survey_pin_tan']['success'], $newCount, $exiCount));
                    break;
                case 'nonanoncode':
                    // generate member-related TANs
                    $this->import('\Hschottm\SurveyBundle\Survey', 'svy');
                    // get all member-groups for this survey
                    $memberGroups = $survey->getRelated('allowed_groups');
                    // check for valid groups
                    if($memberGroups) {
                        // group-restricted survey
                        $newCount = $exiCount = 0;
                        foreach($memberGroups->getModels() as $memberGroup) {
                            if($memberGroup->disable !== '1') {
                                // $members is NULL if the group is empty
                                if($members = $memberGroup->findAllMembers()) {
                                    foreach ($members as $member) {
                                        $isExisting = SurveyPinTanModel::findBy(['pid = ? AND used = 0', 'member_id = ?'], [$survey->id, $member->id]);
                                        if (!$isExisting) {
                                            $pintan = $this->svy->generatePIN_TAN();
                                            $this->insertPinTan($survey->id, $pintan['PIN'], $pintan['TAN'], $member->id);
                                            $newCount++;
                                        } else {
                                            $exiCount++;
                                        }
                                    }
                                } else {
                                    // group is empty
                                    Message::addError(sprintf($GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_empty'],$memberGroup->name));
                                }
                            } else {
                                // group is disabled
                                Message::addError(sprintf($GLOBALS['TL_LANG']['tl_survey_pin_tan']['group_disabled'],$memberGroup->name));
                            };
                        }
                        // show generator result
                        Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_survey_pin_tan']['success'],$newCount,$exiCount));
                    } else {
                        // survey for all members
                        $members = MemberModel::findBy(['disable = ?', 'locked = ?'], ['', '']);

                        if($members) {
                            $newCount = $exiCount =0;
                            foreach ($members as $member) {
                                $isExisting = SurveyPinTanModel::findBy(['pid = ? AND used = 0', 'member_id = ?'],[$survey->id, $member->id]);
                                if(!$isExisting) {
                                    $pintan = $this->svy->generatePIN_TAN();
                                    $this->insertPinTan($survey->id, $pintan['PIN'], $pintan['TAN'], $member->id);
                                    $newCount++;
                                } else {
                                    $exiCount++;
                                }
                            }
                            // show generator result
                            Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_survey_pin_tan']['success'],$newCount,$exiCount));
                        } else {
                            // no members given
                            Message::addError($GLOBALS['TL_LANG']['tl_survey_pin_tan']['error']);
                        };
                    }
                    break;
                default:
                    // do nothing at this time
            }

            $this->redirect(Backend::addToUrl('', true, ['key']));
        }
    }

    /**
     * a group_id was added to this function at 08/2023 to support
     * TAN generation for specific member groups
     *
     * @param $pid
     * @param $pin
     * @param $tan
     * @param $group_id
     *
     * @return void
     */
    protected function insertPinTan($pid, $pin, $tan, $member_id = '0'): void
    {
        $newParticipant = new SurveyPinTanModel();
        $newParticipant->tstamp = time();
        $newParticipant->pid = $pid;
        $newParticipant->pin = $pin;
        $newParticipant->tan = $tan;
        $newParticipant->member_id = $member_id;
        $newParticipant->save();
    }

    /**
     * Return the page tree as object.
     *
     * @param mixed
     * @param mixed|null $value
     *
     * @return object
     */
    protected function getSurveyPageWidget($value = null)
    {
        $widget = new PageTree(Widget::getAttributesFromDca($GLOBALS['TL_DCA']['tl_survey']['fields']['surveyPage'], 'surveyPage', $value, 'surveyPage', 'tl_survey'));

        if ($GLOBALS['TL_CONFIG']['showHelp'] && \strlen($GLOBALS['TL_LANG']['tl_survey']['surveyPage'][1])) {
            $widget->help = $GLOBALS['TL_LANG']['tl_survey']['surveyPage'][1];
        }

        // Valiate input
        if ('tl_export_survey_pin_tan' === Input::post('FORM_SUBMIT')) {
            $widget->validate();

            if ($widget->hasErrors()) {
                $this->blnSave = false;
            }
        }

        return $widget;
    }

    /**
     * Return the TAN widget as object.
     *
     * @param mixed
     * @param mixed|null $value
     *
     * @return object
     */
    protected function getTANWidget($value = null)
    {
        $widget = new TextField();

        $widget->id = 'nrOfTAN';
        $widget->name = 'nrOfTAN';
        $widget->mandatory = true;
        $widget->maxlength = 4;
        $widget->minval = 1;
        $widget->maxval = 9999;
        $widget->rgxp = 'natural';
        $widget->nospace = true;
        $widget->value = $value;
        $widget->required = true;
        $widget->tl_class = 'w50 widget';

        $widget->label = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][0];

        if ($GLOBALS['TL_CONFIG']['showHelp'] && !empty($GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][1])) {
            $widget->help = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][1];
        }

        // Valiate input
        if ('tl_generate_survey_pin_tan' === Input::post('FORM_SUBMIT')) {
            $widget->validate();

            if ($widget->hasErrors()) {
                $this->blnSave = false;
            }
        }

        return $widget;
    }

    public static function formatMember($member_id): string
    {
        if((int)$member_id > 0) {
            if($m = MemberModel::findByPk($member_id)) {
                $member = " $m->firstname $m->lastname";
            } else {
                $member = " Mitglied gelöscht?";
            }
        } else {
            $member = '';
        }

        return $member;
    }
}
