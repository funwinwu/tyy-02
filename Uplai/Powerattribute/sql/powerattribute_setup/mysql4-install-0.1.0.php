<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('powerattribute')};
CREATE TABLE {$this->getTable('powerattribute')} (
  `powerattribute_id` int(11) unsigned NOT NULL auto_increment,
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `option_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `content` varchar(255) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  PRIMARY KEY (`powerattribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 