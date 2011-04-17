<?php

$installer = $this;

$installer->startSetup();

$installer->addAttribute('customer_address', 'city_id', array('type'=>'int'));
$installer->addAttribute('customer_address', 'district_id', array('type'=>'int'));
$installer->addAttribute('customer_address', 'district', array('type'=>'varchar'));

$installer->addAttribute('order_address', 'city_id', array('type'=>'int'));
$installer->addAttribute('order_address', 'district_id', array('type'=>'int'));
$installer->addAttribute('order_address', 'district', array('type'=>'varchar'));

$installer->addAttribute('quote_address', 'city_id', array('type'=>'int'));
$installer->addAttribute('quote_address', 'district_id', array('type'=>'int'));
$installer->addAttribute('quote_address', 'district', array('type'=>'varchar'));

$installer->endSetup(); 