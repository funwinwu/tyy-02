<?php

class Uplai_Oscheckout_Model_Oscheckout extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('oscheckout/oscheckout');
    }
}