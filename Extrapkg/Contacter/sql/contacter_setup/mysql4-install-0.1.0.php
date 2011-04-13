<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('contacter')};
CREATE TABLE {$this->getTable('contacter')} (
  `contacter_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `country` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `zip` varchar(255) NOT NULL default '',
  `conpany_name` varchar(255) NOT NULL default '',
  `conpany_address_01` varchar(255) NOT NULL default '',
  `conpany_address_02` varchar(255) NOT NULL default '',
  `business_year` varchar(255) NOT NULL default '',
  `business_role` varchar(255) NOT NULL default '',
  `business_nuture` varchar(255) NOT NULL default '',
  `no_of_employees` varchar(255) NOT NULL default '',
  `position` varchar(255) NOT NULL default '',
  `cif` varchar(255) NOT NULL default '',
  `tel` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `gift_feedback` varchar(255) NOT NULL default '',
  `product_code` varchar(255) NOT NULL default '',  
  `subject` varchar(255) NOT NULL default '',  
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `type` smallint(6) NOT NULL default '1',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`contacter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 