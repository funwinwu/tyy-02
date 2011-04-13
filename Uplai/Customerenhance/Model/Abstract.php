<?php


class Uplai_Customerenhance_Model_Abstract extends Mage_Core_Model_Abstract
{
	static $db;
	public function __construct()
	{
		$this->db = Mage::getSingleton('core/resource')->getConnection('core_write');	//operate in db directly.
	}
}
