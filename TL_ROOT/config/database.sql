-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_survey`
-- 

CREATE TABLE `tl_survey` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `language` varchar(32) NOT NULL default '',
  `author` smallint(5) unsigned NOT NULL default '0',
  `online_start` varchar(32) NOT NULL default '',
  `online_end` varchar(32) NOT NULL default '',
  `description` text NULL,
  `access` varchar(32) NOT NULL default '',
  `usecookie` char(1) NOT NULL default '',
  `limit_groups` char(1) NOT NULL default '0',
  `show_title` char(1) NOT NULL default '1',
  `show_cancel` char(1) NOT NULL default '1',
  `allowed_groups` blob NULL,
  `introduction` text NOT NULL,
  `finalsubmission` text NOT NULL,
  `allowback` char(1) NOT NULL default '',
  `jumpto` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_result`
-- 

CREATE TABLE `tl_survey_result` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `pin` varchar(16) NOT NULL default '',
  `uid` int(10) unsigned NOT NULL default '0',
  `qid` int(10) unsigned NOT NULL default '0',
  `result` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_pin_tan`
-- 

CREATE TABLE `tl_survey_pin_tan` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `pin` varchar(16) NOT NULL default '',
  `tan` varchar(16) NOT NULL default '',
  `used` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `pin` (`pin`),
  KEY `tan` (`tan`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_participant`
-- 

CREATE TABLE `tl_survey_participant` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `pin` varchar(16) NOT NULL default '',
  `lastpage` int(10) unsigned NOT NULL default '1',
  `finished` char(1) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default ''
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_question`
-- 

CREATE TABLE `tl_survey_question` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `questiontype` varchar(20) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  `author` smallint(5) unsigned NOT NULL default '0',
  `language` varchar(32) NOT NULL default '',
  `question` text NOT NULL,
  `introduction` text NOT NULL,
  `obligatory` char(1) NOT NULL default '',
  `complete` char(1) NOT NULL default '',
  `original` char(1) NOT NULL default '',
  `help` varchar(255) NOT NULL default '',
  `hidetitle` char(1) NOT NULL default '',
  `lower_bound` varchar(32) NOT NULL default '',
  `upper_bound` varchar(32) NOT NULL default '',
  `lower_bound_date` varchar(32) NOT NULL default '',
  `upper_bound_date` varchar(32) NOT NULL default '',
  `lower_bound_time` varchar(32) NOT NULL default '',
  `upper_bound_time` varchar(32) NOT NULL default '',
  `openended_subtype` varchar(32) NOT NULL default '',
  `openended_textbefore` varchar(150) NOT NULL default '',
  `openended_textafter` varchar(150) NOT NULL default '',
  `openended_rows` smallint(5) unsigned NOT NULL default '5',
  `openended_cols` smallint(5) unsigned NOT NULL default '40',
  `openended_width` varchar(4) NOT NULL default '',
  `openended_maxlen` varchar(5) NOT NULL default '',
  `openended_textinside` varchar(150) NOT NULL default '',
  `multiplechoice_subtype` varchar(32) NOT NULL default '',
  `matrix_subtype` varchar(32) NOT NULL default '',
  `mc_style` varchar(32) NOT NULL default '',
  `choices` blob NULL,
  `matrixrows` blob NULL,
  `matrixcolumns` blob NULL,
  `addneutralcolumn` char(1) NOT NULL default '',
  `neutralcolumn` varchar(255) NOT NULL default '',
  `addother` char(1) NOT NULL default '',
  `addbipolar` char(1) NOT NULL default '',
  `adjective1` varchar(255) NOT NULL default '',
  `adjective2` varchar(255) NOT NULL default '',
  `bipolarposition` varchar(32) NOT NULL default '',
  `othertitle` varchar(150) NOT NULL default '',
  `inputfirst` char(1) NOT NULL default '',
  `sumoption` varchar(32) NOT NULL default '',
  `sumchoices` blob NULL,
  `sum` double NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_page`
-- 

CREATE TABLE `tl_survey_page` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  `language` varchar(32) NOT NULL default '',
  `introduction` text NOT NULL,
  `page_template` varchar(255) NOT NULL default 'survey_questionblock',
  `pagetype` varchar(30) NOT NULL default 'standard',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_scale`
-- 

CREATE TABLE `tl_survey_scale` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `pid` int(10) unsigned NOT NULL default '0',
  `description` text NULL,
  `scale` blob NULL,
  `language` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_survey_scale_folder`
-- 

CREATE TABLE `tl_survey_scale_folder` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `survey` smallint(5) unsigned NOT NULL default '0',
  `surveyTpl` varchar(64) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
