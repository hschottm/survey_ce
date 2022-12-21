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

class Survey extends Backend
{
    /**
     * Import String library.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getTANforPIN($id, $pin)
    {
        $pinTanModel = SurveyPinTanModel::findOneBy(['pid=?', 'pin=?'], [$id, $pin]);

        return null !== $pinTanModel ? $pinTanModel->tan : null;
    }

    public function getPINforTAN($id, $tan)
    {
        $pinTanModel = SurveyPinTanModel::findOneBy(['pid=?', 'tan=?'], [$id, $tan]);

        return null !== $pinTanModel ? $pinTanModel->pin : null;
    }

    public function getSurveyStatus($id, $pin)
    {
        $participantModel = SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$id, $pin]);

        if (null !== $participantModel) {
            return $participantModel->finished ? 'finished' : 'started';
        }

        return false;
    }

    /**
     * Checks a PIN and returns FALSE if the pin does not exist, 0 if the pin exists but wasn't used and a timestamp if the pin exists and was used.
     *
     * @param mixed $id
     * @param mixed $pin
     * @param mixed $tan
     *
     * @return string
     */
    public function checkPINTAN($id, $pin = '', $tan = '')
    {
        if (\strlen($pin)) {
            $pinTanModel = SurveyPinTanModel::findOneBy(['pid=?', 'pin=?'], [$id, $pin]);
        } else {
            $pinTanModel = SurveyPinTanModel::findOneBy(['pid=?', 'tan=?'], [$id, $tan]);
        }

        return null !== $pinTanModel ? $pinTanModel->used : false;
    }

    public function getSurveyStatusForMember($id, $uid)
    {
        $participantModel = SurveyParticipantModel::findOneBy(['pid=?', 'uid=?'], [$id, $uid]);

        if (null !== $participantModel) {
            return $participantModel->finished ? 'finished' : 'started';
        }

        return false;
    }

    public function isUserAllowedToTakeSurvey(& $objSurvey)
    {
        $groups = !\strlen($objSurvey->allowed_groups) ? [] : deserialize($objSurvey->allowed_groups, true);

        if (0 === \count($groups)) {
            return false;
        }
        $this->import('\FrontendUser', 'User');

        if (!$this->User->id) {
            return false;
        }
        $usergroups = deserialize($this->User->groups, true);

        if (\count(array_intersect($usergroups, $groups))) {
            return true;
        }

        return false;
    }

    public function getLastPageForPIN($id, $pin)
    {
        $participantModel = SurveyParticipantModel::findOneBy(['pid=?', 'pin=?'], [$id, $pin]);

        return null !== $participantModel ? $participantModel->lastpage : 0;
    }

    public function generatePIN_TAN()
    {
        return [
            'PIN' => $this->generatePIN(),
            'TAN' => $this->generateTAN(),
        ];
    }

    protected function generateCode($length, $type = 'alphanum')
    {
        $codestring = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        switch ($type) {
            case 'alpha':
                $codestring = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;

            case 'num':
                $codestring = '0123456789';
                break;
        }
        mt_srand();
        $code = '';

        for ($i = 1; $i <= $length; ++$i) {
            $index = random_int(0, \strlen($codestring) - 1);
            $code .= substr($codestring, $index, 1);
        }

        return $code;
    }

    protected function generatePIN()
    {
        return $this->generateCode(6);
    }

    protected function generateTAN()
    {
        return $this->generateCode(6, 'num');
    }
}
