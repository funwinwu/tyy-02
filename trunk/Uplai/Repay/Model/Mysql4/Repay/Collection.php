<?php

class Uplai_Repay_Model_Mysql4_Repay_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('repay/repay');
    }
}