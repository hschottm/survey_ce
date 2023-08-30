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
use Contao\Input;
use Contao\Message;
use Contao\StringUtil;
use NotificationCenter\Model\Notification;

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
     * action handler for participiants -> invite.
     *
     *  Creates a list of participants to be invited by mail
     *  to a new survey and sends them a message
     */
    public function invite(DataContainer $dc): string
    {
        if (__FUNCTION__ !== Input::get('key')) {
            return '';
        }

        $id = (int) Input::get('id');
        $hrefBack = "table={$dc->table}&id=".$id;

        if (0 === $id) {
            // no id available or manipulated
            $this::redirect(Backend::addToUrl($hrefBack, true, ['key', 'table', 'id']));
        }
        // find associated survey
        if (null === ($survey = SurveyModel::findByPk($id))) {
            // requested survey not found
            $this::redirect(Backend::addToUrl($hrefBack, true, ['key', 'table', 'id']));
        }

        $this->Template = new BackendTemplate('be_participants_invite');
        // prepare header
        $this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->Template->hrefBack = Backend::addToUrl($hrefBack, true, ['key', 'table', 'id']);
        $this->Template->headline = $GLOBALS['TL_LANG']['tl_survey_participant']['invite'][0];

        // get all valid participants alias members ToDo: member locked? disabled? has email?
        //$pintan = SurveyPinTanModel::findBy(['pid = ?', 'used = 0'], [$survey->id]);
        // get all members of this survey
        $members = $survey->findAllUniqueParticipants();
        // suppress missing emails
        $arrEmails = null === $members ? [] : array_filter($members->fetchEach('email'), static fn ($item) => !empty($item));

        $strEmails = implode(',', $arrEmails);

        // check request method
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (\array_key_exists('send', $_POST)) {
                // send invitations

                // 1. get the invitation notification
                $notification = Notification::findByPk($survey->invitationNotificationId);
                // 2. prepare the notification
                if (null !== $notification) {
                    // we have a valid notification

                    // prepare tokens
                    $arrTokens = [
                        'survey_title' => $survey->title,
                        'survey_all_member_emails' => $strEmails,
                    ];
                    // send
                    if ($notification->send($arrTokens)) {
                        Message::addInfo(
                            sprintf(
                                $GLOBALS['TL_LANG']['tl_survey_participant']['invite_success'],
                                \count($arrEmails)
                            )
                        );
                    } else {
                        Message::addInfo($GLOBALS['TL_LANG']['tl_survey_participant']['invite_error']);
                    }
                } else {
                    // no notification available
                    Message::addError($GLOBALS['TL_LANG']['tl_survey_participant']['invite_no_invitation_available']);
                }

                $this::redirect(Backend::addToUrl($hrefBack, true, ['key', 'table', 'id']));
            } elseif (\array_key_exists('cancel', $_POST)) {
                // cancel sending
                $this::redirect(Backend::addToUrl($hrefBack, true, ['key', 'table', 'id']));
            }
        }
        // prepare buttons
        $this->Template->send = StringUtil::specialchars('Jetzt einladen');
        $this->Template->cancel = StringUtil::specialchars('Abbrechen');

        $this->Template->note = sprintf(
            $GLOBALS['TL_LANG']['tl_survey_participant']['note_template'],
            $survey->title,
            sprintf(
                $GLOBALS['TL_LANG']['tl_survey_participant']['invite_text'],
                'Name der Notification'
            ),
            $GLOBALS['TL_LANG']['tl_survey_participant']['invite_warn'],
            $GLOBALS['TL_LANG']['tl_survey_participant']['invite_hint'],
            \count($arrEmails),
        );

        return $this->Template->parse();
    }

    /**
     * action handler for participiant -> remind.
     *
     * Creates a list of participants to be reminded by mail about their unfinished
     * survey and sends them a message
     */
    public function remind(DataContainer $dc): string
    {
        if (__FUNCTION__ !== Input::get('key')) {
            return '';
        }

        return $this->Template->parse();
    }
}
