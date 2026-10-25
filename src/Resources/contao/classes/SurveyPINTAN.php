<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle;

use Contao\DataContainer;
use Hschottm\SurveyBundle\Export\Exporter;
use Hschottm\SurveyBundle\Export\ExportHelper;

/**
 * Class SurveyPINTAN.
 *
 * Provide methods to handle import and export of member data.
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class SurveyPINTAN extends \Contao\Backend
{
    protected $blnSave = true;

    public function exportTAN(DataContainer $dc)
    {
        if ('exporttan' != \Contao\Input::get('key')) {
            return '';
        }

        if ('tl_survey_pin_tan' == \Contao\Input::get('table')) {
            $this->redirect(\Contao\Backend::addToUrl('table=tl_survey', true, ['table']));

            return;
        }

        $this->loadLanguageFile('tl_survey_pin_tan');
        $this->Template = new \Contao\BackendTemplate('be_survey_export_tan');

        $this->Template->surveyPage = $this->getSurveyPageWidget();

        $this->Template->hrefBack = \Contao\Backend::addToUrl('table=tl_survey_pin_tan', true, ['table', 'key']);
        $this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->headline = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['exporttan'];
        $this->Template->request = \Contao\StringUtil::ampersand(str_replace('&id=', '&pid=', \Contao\Environment::get('request')));
        $this->Template->submit = \Contao\StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_pin_tan']['export']);

        // Create import form
        if ('tl_export_survey_pin_tan' == \Contao\Input::post('FORM_SUBMIT') && $this->blnSave) {
            $export = [];
            $surveyPage = $this->Template->surveyPage->value;
            $pageModel = \Contao\PageModel::findOneBy('id', $surveyPage);
            $pagedata = (null != $pageModel) ? $pageModel->row() : null;
            $domain = \Contao\Environment::get('base');

            $res = \Hschottm\SurveyBundle\SurveyPinTanModel::findBy('pid', \Contao\Input::get('pid'), ['order' => 'tstamp DESC, id DESC']);
            foreach ($res as $objPINTAN) {
                $row = $objPINTAN->row();
                $line = [];
                $line['tan'] = $row['tan'];
                $line['tstamp'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $row['tstamp']);
                $line['used'] = $row['used'] ? 1 : 0;
                if (null != $pageModel) {
                    $line['url'] = \Contao\StringUtil::ampersand($pageModel->getAbsoluteUrl().(str_contains($pageModel->getAbsoluteUrl(), '?') ? '&' : '?').'code='.rawurlencode($row['tan']));
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
                    Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD
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
                    Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO
                  ]
                );
                ++$intColCounter;

                $exporter->setCellValue(
                  $sheet,
                  $intRowCounter,
                  $intColCounter,
                  [
                    Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['used'][0],
                    Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD
                  ]
                );
                ++$intColCounter;

                if (null != $pagedata) {
                    $exporter->setCellValue(
                      $sheet,
                      $intRowCounter,
                      $intColCounter,
                      [
                        Exporter::DATA => $GLOBALS['TL_LANG']['tl_survey_pin_tan']['url'],
                        Exporter::FONTWEIGHT => Exporter::FONTWEIGHT_BOLD,
                        Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO
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
                    Exporter::COLWIDTH => Exporter::COLWIDTH_AUTO
                  ]
                );

                // Data
                $intRowCounter = 1;
                foreach ($export as $line) {
                    $intColCounter = 0;
                    foreach ($line as $key => $data) {
                        $celldata = [
                          Exporter::DATA => $data
                        ];
                        if ($intColCounter == 0)
                        {
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
                        Exporter::CELLTYPE => Exporter::CELLTYPE_FLOAT
                      ]
                    );
                    ++$intRowCounter;
                }
                $surveyModel = \Hschottm\SurveyBundle\SurveyModel::findOneBy('id', \Contao\Input::get('pid'));
                if (null != $surveyModel) {
                  $exporter->setFilename('TAN_'.$surveyModel->title);
                } else {
                  $exporter->setFilename('TAN');
                }
                $exporter->sendFile("TAN", "TAN", "TAN", 'Contao CMS', 'Contao CMS');
                exit;
            }
            $this->redirect(\Contao\Backend::addToUrl('table=tl_survey_pin_tan', true, ['key', 'table']));
        }

        return $this->Template->parse();
    }

    public function createTAN(DataContainer $dc)
    {
        if ('createtan' != \Contao\Input::get('key')) {
            return '';
        }

        $this->loadLanguageFile('tl_survey_pin_tan');
        $this->Template = new \Contao\BackendTemplate('be_survey_create_tan');

        $this->Template->nrOfTAN = $this->getTANWidget();

        $this->Template->hrefBack = \Contao\StringUtil::ampersand(str_replace('&key=createtan', '', \Contao\Environment::get('request')));
        $this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->headline = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['createtan'];
        $this->Template->request = \Contao\StringUtil::ampersand(\Contao\Environment::get('request'), true);
        $this->Template->submit = \Contao\StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_pin_tan']['create']);

        // Create import form
        if ('tl_export_survey_pin_tan' == \Contao\Input::post('FORM_SUBMIT') && $this->blnSave) {
            $nrOfTAN = $this->Template->nrOfTAN->value;
            $this->import('\Hschottm\SurveyBundle\Survey', 'svy');
            for ($i = 0; $i < ceil($nrOfTAN); ++$i) {
                $pintan = $this->svy->generatePIN_TAN();
                $this->insertPinTan(\Contao\Input::get('id'), $pintan['PIN'], $pintan['TAN']);
            }
            $this->redirect(\Contao\Backend::addToUrl('', true, ['key']));
        }

        return $this->Template->parse();
    }

    protected function insertPinTan($pid, $pin, $tan)
    {
        $newParticipant = new \Hschottm\SurveyBundle\SurveyPinTanModel();
        $newParticipant->tstamp = time();
        $newParticipant->pid = $pid;
        $newParticipant->pin = $pin;
        $newParticipant->tan = $tan;
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
        $widget = new \Contao\PageTree(\Contao\Widget::getAttributesFromDca($GLOBALS['TL_DCA']['tl_survey']['fields']['surveyPage'], 'surveyPage', $value, 'surveyPage', 'tl_survey'));
        if ($GLOBALS['TL_CONFIG']['showHelp'] && \strlen((string) $GLOBALS['TL_LANG']['tl_survey']['surveyPage'][1])) {
            $widget->help = $GLOBALS['TL_LANG']['tl_survey']['surveyPage'][1];
        }

        // Valiate input
        if ('tl_export_survey_pin_tan' == \Contao\Input::post('FORM_SUBMIT')) {
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
        $widget = new \Contao\TextField();

        $widget->id = 'nrOfTAN';
        $widget->name = 'nrOfTAN';
        $widget->mandatory = true;
        $widget->maxlength = 5;
        $widget->rgxp = 'digit';
        $widget->nospace = true;
        $widget->value = $value;
        $widget->required = true;

        $widget->label = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][0];

        if ($GLOBALS['TL_CONFIG']['showHelp'] && \strlen((string) $GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][1])) {
            $widget->help = $GLOBALS['TL_LANG']['tl_survey_pin_tan']['nrOfTAN'][1];
        }

        // Valiate input
        if ('tl_export_survey_pin_tan' == \Contao\Input::post('FORM_SUBMIT')) {
            $widget->validate();

            if ($widget->hasErrors()) {
                $this->blnSave = false;
            }
        }

        return $widget;
    }
}
