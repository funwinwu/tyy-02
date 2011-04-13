<?php

class Uplai_Repay_Model_Mysql4_Repay extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the repay_id refers to the key field in your database table.
        $this->_init('repay/repay', 'repay_id');
    }
}