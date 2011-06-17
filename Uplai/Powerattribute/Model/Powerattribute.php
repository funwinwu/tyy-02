<?php

class Uplai_Powerattribute_Model_Powerattribute extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('powerattribute/powerattribute');
    }
}