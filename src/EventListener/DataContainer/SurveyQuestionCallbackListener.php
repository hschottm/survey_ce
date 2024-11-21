<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\Input;
use Symfony\Contracts\Translation\TranslatorInterface;
use Hschottm\SurveyBundle\SurveyQuestionModel;
use Doctrine\DBAL\Connection;

class SurveyQuestionCallbackListener
{
    public function __construct(
        private readonly TranslatorInterface $translator, 
        private readonly Connection $db,
    )
    {
    }
    
    #[AsCallback(table: 'tl_survey_question', target: 'list.label.label')]
    public function formatColumnView(array $row, string $label, DataContainer $dc): string
    {
        $fields = $GLOBALS['TL_DCA'][$dc->table]['list']['label']['fields'];

        $widget = '';
        $strClass = $GLOBALS['TL_SVY'][$row['questiontype']];

        $template = new FrontendTemplate('be_survey_question_preview');
        $template->hidetitle = $row['hidetitle'];
        $template->help = StringUtil::specialchars($row['help']);
        $template->questionNumber = $this->getQuestionNumber($row);
        $template->title = StringUtil::specialchars($row['title']);
        $template->obligatory = $row['obligatory'];
        $template->question = $row['question'];
        $return = $template->parse();

        if (class_exists($strClass)) {
            $row['hidetitle'] = true;
            $objWidget = new $strClass();
            $objWidget->surveydata = $row;
            $widget = $objWidget->generate();
        }

        $return .= $widget;

        return $return;
    }

    protected function getQuestionNumber($row)
    {
        $surveyQuestionCollection = SurveyQuestionModel::findBy(['pid=?', 'sorting<=?'], [$row['pid'], $row['sorting']]);
        return (null != $surveyQuestionCollection) ? $surveyQuestionCollection->count() : 0;
    }

    #[AsCallback(table: 'tl_survey_question', target: 'config.onsubmit')]
    public function surveyQuestionSubmit(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        $this->db->prepare('UPDATE tl_survey_question SET complete = ?, original = ? WHERE id=?')
            ->execute(array(1, 1, $dc->id));
    }

    #[AsCallback(table: 'tl_survey_question', target: 'list.sorting.child_record')]
    public function surveyQuestionSortChildRecord(array $recordData): string
    {
        $widget = '';
        $strClass = $GLOBALS['TL_SVY'][$recordData['questiontype']];
        if (class_exists($strClass)) {
            $objWidget = new $strClass();
            $objWidget->surveydata = $recordData;
            $widget = $objWidget->generate();
        }

        $template = new FrontendTemplate('be_survey_question_preview');
        $template->hidetitle = $recordData['hidetitle'];
        $template->help = StringUtil::specialchars($recordData['help']);
        $template->questionNumber = $this->getQuestionNumber($recordData);
        $template->title = StringUtil::specialchars($recordData['title']);
        $template->obligatory = $recordData['obligatory'];
        $template->question = $recordData['question'];
        $return = $template->parse();
        $return .= $widget;

        return $return;
    }

    #[AsCallback(table: 'tl_survey_question', target: 'fields.alias.save')]
    public function saveAlias($value, DataContainer $dc)
    {
        $autoAlias = false;

        // Generiere einen Alias wenn es keinen gibt
        if ($value == '') {
            $autoAlias = true;
            $value = StringUtil::generateAlias($dc->activeRecord->title);
        }
        // Die gewünschte Tabelle zuweisen, aus der ein auto- Alias generiert werden soll.
        // Input::get('table') lassen, wenn die Tabelle dynamisch zugeordnet werden soll.
        $table = Input::get('table') ? Input::get('table') : 'tl_survey_question';
        $objAlias = $this->db->prepare("SELECT id FROM " . $table . " WHERE alias=?")->execute(array($value));
        // Überprüfe ob der Alias bereits existiert.
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
        }
        // wenn alias bereits existiert, füge eine ID hinzu.
        if ($objAlias->numRows && $autoAlias) {
            $value .= '-' . $dc->id;
        }
        return $value;
    }

    #[AsCallback(table: 'tl_survey_question', target: 'fields.choices.wizard')]
    public function choicesWizard(DataContainer $dc): string
    {
        if (!$dc->id) {
            return "";
        }

        $objQuestion = $this->db->prepare('SELECT multiplechoice_subtype FROM tl_survey_question WHERE id=?')
            ->limit(1)
            ->execute(array($dc->id));
        if (0 == strcmp($objQuestion->multiplechoice_subtype, 'mc_singleresponse')) {
            return '<a class="tl_submit" style="margin-top: 10px;" href="'.$this->addToUrl('key=scale').'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][1]).'" onclick="Backend.getScrollOffset();">'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_survey_question']['addscale'][0]).'</a>';
        }

        return '';
    }

}
