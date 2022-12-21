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

$GLOBALS['TL_DCA']['tl_survey_navigation'] = [
    // Config
    'config' => [
        'ptable' => 'tl_survey',
        'doNotCopyRecords' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pin' => [
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'uid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'frompage' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'topage' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];
