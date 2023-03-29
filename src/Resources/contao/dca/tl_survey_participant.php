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

use Contao\Backend;
use Contao\Input;
use Contao\MemberModel;
use Contao\System;
use Hschottm\SurveyBundle\SurveyPageModel;
use Hschottm\SurveyBundle\SurveyParticipantModel;

$GLOBALS['TL_DCA']['tl_survey_participant'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_survey',
        'doNotCopyRecords' => true,
        'enableVersioning' => true,
        'closed' => true,
        'onload_callback' => [
            ['tl_survey_participant', 'checkPermission'],
        ],
        'ondelete_callback' => [
            ['tl_survey_participant', 'deleteParticipant'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['lastpage', 'tstamp'],
            'flag' => 11, // sort ASC ungrouped  on initial display
            'panelLayout' => 'filter;sort,limit',
        ],
        'label' => [
            'fields' => ['pin', 'uid', 'finished'],
            'label_callback' => ['tl_survey_participant', 'getLabel'],
        ],
        'global_operations' => [
            'exportraw' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_participant']['exportraw'],
                'href' => 'key=exportraw',
                'class' => 'header_export',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'deleteall' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['deleteAll'],
                'href' => 'act=deleteAll',
                'class' => 'header_delete_all',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['delAllConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_participant']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_survey_participant']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],
    // Palettes
    'palettes' => [
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_participant']['tstamp'],
            'sorting' => true,
            'flag' => 5, // sort ASC grouped by day
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'rgxp' => 'datim', 'insertTag' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'uid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pin' => [
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'lastpage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_survey_participant']['lastpage'],
            'sorting' => true,
            'flag' => 3, // sort ASC grouped by first X chars
            'length' => 2, // group by first 2 chars
            'filter' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 16, 'rgxp' => 'digit'],
            'sql' => "int(10) unsigned NOT NULL default '1'",
        ],
        'finished' => [
            'sql' => "char(1) NOT NULL default ''",
        ],
        'email' => [
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'firstname' => [
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'lastname' => [
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'company' => [
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'category' => [
            'sql' => 'int(10) unsigned NULL',
        ],
    ],
];

/**
 * Class tl_survey_participant.
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 */
class tl_survey_participant extends Backend
{
    protected $pageCount;

    /**
     * Check permissions to edit table tl_survey_participant.
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function checkPermission(): void
    {
        switch (Input::get('act')) {
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
              $objSession = System::getContainer()->get('session');
              $session = $objSession->all();
              $res = SurveyParticipantModel::findBy('pid', Input::get('id'));

              if (null !== $res && $res->count() >= 1) {
                  $session['CURRENT']['IDS'] = array_values($res->fetchEach('id'));
                  $objSession->replace($session);
              }
              break;

          default:
              if (Input::get('act')) {
                  throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid command "'.Input::get('act').'.');
              }
              break;
      }
    }

    public function deleteParticipant($dc): void
    {
        $res = SurveyParticipantModel::findOneBy('id', $dc->id);

        if (null !== $res) {
            setcookie('TLsvy_'.$res->pid, $res->pin, time() - 3600, '/');
            $objDelete = $this->Database->prepare('DELETE FROM tl_survey_pin_tan WHERE (pid=? AND pin=?)')->execute($res->pid, $res->pin);
            $objDelete = $this->Database->prepare('DELETE FROM tl_survey_result WHERE (pid=? AND pin=?)')->execute($res->pid, $res->pin);
            $objDelete = $this->Database->prepare('DELETE FROM tl_survey_navigation WHERE (pid=? AND pin=?)')->execute($res->pid, $res->pin);
        }
    }

    public function getUsername($uid)
    {
        $user = MemberModel::findOneBy('id', $uid);

        if (null !== $user) {
            return trim($user->firstname.' '.$user->lastname);
        }

        return '';
    }

    public function getLabel($row, $label)
    {
        // we ignore the label param, the row has it all
        $finished = (int) ($row['finished']);

        return sprintf(
            '<div>%s, <strong>%s</strong> <span style="color: #7f7f7f;">[%s%s]</span></div>',
            date($GLOBALS['TL_CONFIG']['datimFormat'], $row['tstamp']),
            $row['uid'] > 0
                ? $this->getUsername($row['uid'])
                : $row['pin'],
            $finished
                ? $GLOBALS['TL_LANG']['tl_survey_participant']['finished']
                : $GLOBALS['TL_LANG']['tl_survey_participant']['running'],
            $finished
                ? ''
                : ' ('.$row['lastpage'].'/'.$this->getPageCount($row['pid']).')'
        );
    }

    /**
     * Returns the surveys number of pages (cached).
     *
     * @param int
     * @param mixed $survey_id
     *
     * @return int
     */
    protected function getPageCount($survey_id)
    {
        if (!isset($this->pageCount)) {
            $res = SurveyPageModel::findBy('pid', $survey_id);

            if (null !== $res) {
                $this->pageCount = $res->count();
            } else {
                $this->pageCount = 0;
            }
        }

        return $this->pageCount;
    }
}
