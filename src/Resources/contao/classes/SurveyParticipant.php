<?php

namespace Hschottm\SurveyBundle;

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\DataContainer;
use Contao\Input;
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

        $this->Template = new BackendTemplate('be_participants_invite');
        // preape header
        $this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->hrefBack = Backend::addToUrl($hrefBack, true, ['key','table','id']);
        // prepare actions
        $this->Template->send = StringUtil::specialchars('Jetzt einladen');
        $this->Template->cancel = StringUtil::specialchars('Abbrechen');

        // check request method
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(array_key_exists('send', $_POST)) {
                // send all invitations


            }
            elseif(array_key_exists('cancel', $_POST)) {
                // cancel sending
                $this::redirect(Backend::addToUrl($hrefBack, true, ['key','table','id']));
            }
        }

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
