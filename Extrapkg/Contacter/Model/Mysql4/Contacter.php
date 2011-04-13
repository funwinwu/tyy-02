<?php

class Extrapkg_Contacter_Model_Mysql4_Contacter extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the contacter_id refers to the key field in your database table.
        $this->_init('contacter/contacter', 'contacter_id');
    }
}