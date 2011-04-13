<?php

class Extrapkg_Magicimport_Model_Datatype extends Varien_Object
{
    const TYPE_PRODUCT	= 1;
    const TYPE_CATEGORY	= 2;
    

    static public function getOptionArray()
    {
        return array(
            self::TYPE_PRODUCT    => Mage::helper('magicimport')->__('Product'),
            self::TYPE_CATEGORY   => Mage::helper('magicimport')->__('Category'),           
        );
    }
}