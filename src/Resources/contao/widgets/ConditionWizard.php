<?php

/**
 * @copyright  Helmut Schottmüller 2008-2019
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @package    Backend
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 */

namespace Hschottm\SurveyBundle;

use Contao\StringUtil;

/**
 * Class ConditionWizard
 *
 * Provide a backend wizard to handle jump conditions
 *
 * @property integer $maxlength
 */
class ConditionWizard extends \Widget
{
	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
      case 'maxlength':
				if ($varValue > 0)
				{
					$this->arrAttributes['maxlength'] = $varValue;
				}
				break;

			case 'value':
				$this->varValue = StringUtil::deserialize($varValue);
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
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
    $tpl = new \BackendTemplate('be_condition_wizard');
    return $tpl->parse();
  }
}

class_alias(ConditionWizard::class, 'ConditionWizard');
