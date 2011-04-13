<?php

class Uplai_Oscheckout_Model_Mysql4_Oscheckout_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('oscheckout/oscheckout');
    }
}