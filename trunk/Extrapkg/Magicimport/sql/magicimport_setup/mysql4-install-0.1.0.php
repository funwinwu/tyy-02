<?php

$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('magicimport')};
CREATE TABLE {$this->getTable('magicimport')} (
  `magicimport_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `main_status` set('p','f','c','r'),
  `import_type` tinyint not null default 0,
  `data_type` tinyint not null default 0,
  `content` text NOT NULL default '',
  `success` int NOT NULL default '0',
  `failed` int NOT NULL default '0',  
  `data_rows` int NOT NULL default '0',
  `store_id` int NOT NULL default '0',  
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`magicimport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('magicdata')} (
  `magicdata_id` int(11) unsigned NOT NULL auto_increment,
  `magicimport_id` int(11) unsigned NOT NULL default 0,  
  `content` text NOT NULL default '',
  `status` set('p','f','c','r'),
  `backup` text NOT NULL default '',
  `hash` varchar(32) null,
  `updated` int NOT NULL default '0',  
  `deleted` int NOT NULL default '0',
  `affected_entites` int NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`magicdata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup(); 