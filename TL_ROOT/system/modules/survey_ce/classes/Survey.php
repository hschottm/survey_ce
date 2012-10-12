<?php

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class Survey
 *
 * @copyright  Helmut Schottmüller 2009-2010
 * @author     Helmut Schottmüller <contao@aurealis.de>
 */
class Survey extends Backend
{

	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('String');
	}
	
	public function getTANforPIN($id, $pin)
	{
		$objPINTAN = $this->Database->prepare("SELECT tan FROM tl_survey_pin_tan WHERE (pid=? AND pin=?)")
			->execute($id, $pin);
		return $objPINTAN->tan;
	}
	
	public function getPINforTAN($id, $tan)
	{
		$objPINTAN = $this->Database->prepare("SELECT pin FROM tl_survey_pin_tan WHERE (pid=? AND tan=?)")
			->execute($id, $tan);
		return $objPINTAN->pin;
	}
	
	public function getSurveyStatus($id, $pin)
	{
		$objParticipant = $this->Database->prepare("SELECT * FROM tl_survey_participant WHERE (pid=? AND pin=?)")
			->execute($id, $pin);
		if ($objParticipant->numRows)
		{
			$objParticipant->next();
			if ($objParticipant->finished)
			{
				return "finished";
			}
			else
			{
				return "started";
			}
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Checks a PIN and returns FALSE if the pin does not exist, 0 if the pin exists but wasn't used and a timestamp if the pin exists and was used
	 * @return string
	 */
	public function checkPINTAN($id, $pin = "", $tan = "")
	{
		if (strlen($pin))
		{
			$objResult = $this->Database->prepare("SELECT pin, tan, used FROM tl_survey_pin_tan WHERE pid = ? AND pin = ?")
				->execute($id, $pin);
		}
		else
		{
			$objResult = $this->Database->prepare("SELECT pin, tan, used FROM tl_survey_pin_tan WHERE pid = ? AND tan = ?")
				->execute($id, $tan);
		}
		if ($objResult->numRows)
		{
			return $objResult->used;
		}
		else
		{
			return false;
		}
	}


	public function getSurveyStatusForMember($id, $uid)
	{
		$objParticipant = $this->Database->prepare("SELECT * FROM tl_survey_participant WHERE (pid=? AND uid=?)")
			->execute($id, $uid);
		if ($objParticipant->numRows)
		{
			$objParticipant->next();
			if ($objParticipant->finished)
			{
				return "finished";
			}
			else
			{
				return "started";
			}
		}
		else
		{
			return FALSE;
		}
	}

	protected function generateCode($length, $type = 'alphanum')
	{
		$codestring = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		switch ($type)
		{
			case 'alpha':
				$codestring = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
			case 'num':
				$codestring = "0123456789";
				break;
		}
		mt_srand();
		$code = "";
		for ($i = 1; $i <= $length; $i++)
		{
			$index = mt_rand(0, strlen($codestring)-1);
			$code .= substr($codestring, $index, 1);
		}
		return $code;
	}
	
	public function isUserAllowedToTakeSurvey(&$objSurvey)
	{
		$groups = (!strlen($objSurvey->allowed_groups)) ? array() : deserialize($objSurvey->allowed_groups, true);
		if (count($groups) == 0) return false;
		$this->import('FrontendUser', 'User');
		if (!$this->User->id) return false;
		$usergroups = deserialize($this->User->groups, true);
		if (count(array_intersect($usergroups, $groups)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getLastPageForPIN($id, $pin)
	{
		$objParticipant = $this->Database->prepare("SELECT lastpage FROM tl_survey_participant WHERE (pid=? AND pin=?)")
			->execute($id, $pin);
		return $objParticipant->lastpage;
	}
	
	protected function generatePIN()
	{
		return $this->generateCode(6);
	}
	
	protected function generateTAN()
	{
		return $this->generateCode(6, 'num');
	}
	
	public function generatePIN_TAN()
	{
		return array(
			"PIN" => $this->generatePIN(),
			"TAN" => $this->generateTAN()
			);
	}
}

?>