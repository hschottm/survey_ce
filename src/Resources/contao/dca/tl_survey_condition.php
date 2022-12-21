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

$GLOBALS['TL_DCA']['tl_survey_condition'] = [
    // Config
    'config' => [
        'ptable' => 'tl_survey_page',
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
        'grp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'qid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'pageid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'relation' => [
            'sql' => "varchar(2) NOT NULL default '='",
        ],
        'condition' => [
            'sql' => "varchar(1024) NOT NULL default ''",
        ],
    ],
];
