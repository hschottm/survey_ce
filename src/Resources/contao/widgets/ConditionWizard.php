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

use Contao\BackendTemplate;
use Contao\Widget;

/**
 * Class ConditionWizard.
 *
 * Provide a backend wizard to handle jump conditions
 *
 * @property int $maxlength
 */
class ConditionWizard extends Widget
{
    /**
     * Submit user input.
     *
     * @var bool
     */
    protected $blnSubmitInput = true;

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Add specific attributes.
     *
     * @param string $strKey
     * @param mixed  $varValue
     */
    public function __set($strKey, $varValue): void
    {
        switch ($strKey) {
      case 'maxlength':
                if ($varValue > 0) {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;

            case 'value':
                $this->varValue = deserialize($varValue);
                break;

            case 'mandatory':
                $this->arrConfiguration['mandatory'] = $varValue ? true : false;
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        $tpl = new BackendTemplate('be_condition_wizard');

        return $tpl->parse();
    }
}

class_alias(ConditionWizard::class, 'ConditionWizard');
