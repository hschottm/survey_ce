<?php

namespace Hschottm\SurveyBundle;

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\DataContainer;
use Contao\Input;
use Contao\MemberModel;
use Contao\Message;
use Contao\StringUtil;

class SurveyParticipant extends Backend
{
    protected $blnSave = true;

    /**
     * Load the database object.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * action handler for participiants -> invite
     *
     *  Creates a list of participants to be invited by mail
     *  to a new survey and sends them a message
     *
     * @param DataContainer $dc
     * @return string
     */
    public function invite(DataContainer $dc):string
    {
        if (__FUNCTION__ !== Input::get('key')) return '';

        $id = (int) Input::get('id');
        $hrefBack = "table={$dc->table}&id=".$id;

        if($id === 0 && is_null($survey = SurveyModel::findByPk($id))) {
            $this::redirect(Backend::addToUrl($hrefBack, true, ['key','table','id']));
        }

        $survey = SurveyModel::findByPk($id);

        $this->Template = new BackendTemplate('be_participants_invite');
        // preape header
        $this->Template->back       = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->hrefBack   = Backend::addToUrl($hrefBack, true, ['key','table','id']);
        $this->Template->headline   = $GLOBALS['TL_LANG']['tl_survey_participant']['invite'][0];

        // check request method
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(array_key_exists('send', $_POST)) {
                // send all invitations

                Message::addInfo('versendet');

                $this::redirect(Backend::addToUrl($hrefBack, true, ['key','table','id']));
            }
            elseif(array_key_exists('cancel', $_POST)) {
                // cancel sending
                $this::redirect(Backend::addToUrl($hrefBack, true, ['key','table','id']));
            }
        }
        // count member
        $member = SurveyPinTanModel::findBy(['pid = ?', 'used = 0'],[$survey->id]);
        $mailsCount = count($member);

        // prepare buttons
        $this->Template->send       = StringUtil::specialchars("Jetzt einladen");
        $this->Template->cancel     = StringUtil::specialchars('Abbrechen');

        $this->Template->note = sprintf(
            $GLOBALS['TL_LANG']['tl_survey_participant']['note_template'],
            $survey->title,
            sprintf(
                $GLOBALS['TL_LANG']['tl_survey_participant']['invite_text'],
                'Name der Notification'
            ),
            $GLOBALS['TL_LANG']['tl_survey_participant']['invite_warn'],
            $GLOBALS['TL_LANG']['tl_survey_participant']['invite_hint'],
            $mailsCount,
        );

        return $this->Template->parse();
    }

    /**
     * action handler for participiant -> remind
     *
     * Creates a list of participants to be reminded by mail about their unfinished
     * survey and sends them a message
     *
     * @param DataContainer $dc
     * @return string
     */
    public function remind(DataContainer $dc):string
    {
        if (__FUNCTION__ !== Input::get('key')) return '';

        return $this->Template->parse();
    }
}
