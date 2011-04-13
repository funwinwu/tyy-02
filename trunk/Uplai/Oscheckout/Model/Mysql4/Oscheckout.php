<?php

class Uplai_Oscheckout_Model_Mysql4_Oscheckout extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the oscheckout_id refers to the key field in your database table.
        $this->_init('oscheckout/oscheckout', 'oscheckout_id');
    }
}