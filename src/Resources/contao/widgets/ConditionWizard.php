<?php

namespace Hschottm\SurveyBundle;

use Contao\BackendTemplate;
use Contao\Widget;
use Hschottm\SurveyBundle\Condition\Editor;

class ConditionWizard extends Widget
{
    protected $blnSubmitInput = true;
    protected $strTemplate = 'be_widget';

    protected function validator($input)
    {
        try {
            Editor::validate((string) $input, (int) $this->objDca->id);
        } catch (\InvalidArgumentException $exception) {
            $this->addError($exception->getMessage());
        }
        return $input;
    }

    public function generate()
    {
        $template = new BackendTemplate('be_condition_wizard');
        $template->name = $this->strName;
        $template->id = $this->strId;
        $template->value = is_string($this->varValue) && $this->varValue !== '' ? $this->varValue : '[]';
        $template->configuration = json_encode(Editor::configuration((int) $this->objDca->id), JSON_THROW_ON_ERROR);
        $template->labels = $GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor'];
        return $template->parse();
    }
}

class_alias(ConditionWizard::class, 'ConditionWizard');
