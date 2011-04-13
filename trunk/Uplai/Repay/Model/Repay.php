<?php

class Uplai_Repay_Model_Repay extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('repay/repay');
    }
}