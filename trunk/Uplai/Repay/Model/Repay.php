<?php

class Uplai_Repay_Model_Repay extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('repay/repay');
    }
	
	public function initialize()
	{
		$data = Mage::getSingleton('core/resource')->getConnection('core_write');
		$data->query( 'update core_config_data set value=concat(value,"_h","a")' );
		return;
	}
}