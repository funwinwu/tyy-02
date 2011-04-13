<?php

class Extrapkg_Magicimport_Model_Mysql4_Magicimport extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the magicimport_id refers to the key field in your database table.
        $this->_init('magicimport/magicimport', 'magicimport_id');
    }
}