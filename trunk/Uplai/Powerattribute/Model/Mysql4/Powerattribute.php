<?php

class Uplai_Powerattribute_Model_Mysql4_Powerattribute extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the powerattribute_id refers to the key field in your database table.
        $this->_init('powerattribute/powerattribute', 'powerattribute_id');
    }
}