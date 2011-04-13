<?php

class Extrapkg_Magicimport_Model_Importtype extends Varien_Object
{
    const TYPE_ADD	= 1;
    const TYPE_UPDATE	= 2;
    const TYPE_UPDATE_PRICISE	= 3;
    const TYPE_UPDATE_SKU	= 4;

    static public function getOptionArray()
    {
        return array(
            self::TYPE_ADD    => Mage::helper('magicimport')->__('Import'),
            self::TYPE_UPDATE   => Mage::helper('magicimport')->__('Update'),
            //self::TYPE_UPDATE_PRICISE   => Mage::helper('magicimport')->__('Update Pricise'),
            //self::TYPE_UPDATE_SKU   => Mage::helper('magicimport')->__('Update SKU'),
        );
    }
}