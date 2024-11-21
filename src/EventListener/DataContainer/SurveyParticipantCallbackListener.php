<?php

namespace Hschottm\SurveyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Contao\System;
use Contao\StringUtil;
use Contao\Input;
use Symfony\Contracts\Translation\TranslatorInterface;
use Hschottm\SurveyBundle\SurveyParticipantModel;
use Hschottm\SurveyBundle\SurveyPageModel;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;
use Contao\CoreBundle\Exception\AccessDeniedException;

class SurveyParticipantCallbackListener
{
    public function __construct(
        private readonly TranslatorInterface $translator, 
        private readonly Connection $db,
        private readonly RequestStack $requestStack
    )
    {
    }

    #[AsCallback(table: 'tl_survey_participant', target: 'config.ondelete')]
    public function onDeleteParticipant(DataContainer $dc, int $undoId): void
    {
        if (!$dc->id) {
            return;
        }

        $res = SurveyParticipantModel::findOneBy('id', $dc->id);
        if (null != $res) {
            System::setCookie('TLsvy_'.$res->pid, $res->pin, time() - 3600, '/');
            $this->db->prepare('DELETE FROM tl_survey_pin_tan WHERE (pid=? AND pin=?)')->execute(array($res->pid, $res->pin));
            $this->db->prepare('DELETE FROM tl_survey_result WHERE (pid=? AND pin=?)')->execute(array($res->pid, $res->pin));
            $this->db->prepare('DELETE FROM tl_survey_navigation WHERE (pid=? AND pin=?)')->execute(array($res->pid, $res->pin));
        }
    }

    protected function getUsername($uid)
    {
        $user = MemberModel::findOneBy('id', $uid);
        if (null != $user) {
            return trim($user->firstname.' '.$user->lastname);
        }
        return '';
    }

    protected function getPageCount($survey_id)
    {
        $pageCount = 0;
        $res = SurveyPageModel::findBy('pid', $survey_id);
        if (null != $res) {
            $pageCount = $res->count();
        }
        return $pageCount;
    }

    #[AsCallback(table: 'tl_survey_participant', target: 'list.label.label')]
    public function formatListLabel(array $row, string $label, DataContainer $dc, array $labels): string
    {
        // we ignore the label param, the row has it all
        $finished = (int) ($row['finished']);
        $result = sprintf(
            '<div>%s, <strong>%s</strong> <span style="color: #7f7f7f;">[%s%s]</span></div>',
            date($GLOBALS['TL_CONFIG']['datimFormat'], $row['tstamp']),
            ($row['uid'] > 0)
                ? $this->getUsername($row['uid'])
                : $row['pin'],
            ($finished)
                ? $this->translator->trans('tl_survey_participant.finished')
                : $this->translator->trans('tl_survey_participant.running'),
            ($finished)
                ? ''
                : ' ('.$row['lastpage'].'/'.$this->getPageCount($row['pid']).')'
        );

        return $result;
    }

    #[AsCallback(table: 'tl_survey_participant', target: 'config.onload')]
    public function onLoadParticipant(DataContainer|null $dc = null): void
    {
        if (null === $dc || !$dc->id) {
            return;
        }

        switch ($this->requestStack->getCurrentRequest()->query->get('act')) {
            case 'select':
            case 'show':
            case 'edit':
            case 'delete':
            case 'toggle':
                // Allow
                break;
            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
                /** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
                $objSession = System::getContainer()->get('request_stack')->getSession();
                $session = $objSession->all();
                $res = SurveyParticipantModel::findBy('pid', Input::get('id'));
                if (null != $res && $res->count() >= 1) {
                    $session['CURRENT']['IDS'] = array_values($res->fetchEach('id'));
                    $objSession->replace($session);
                }
                break;
            default:
                if (\strlen(Input::get('act'))) {
                    throw new AccessDeniedException('Invalid command "'.Input::get('act').'.');
                }
                break;
        }
      }
}
