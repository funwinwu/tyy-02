<?php

class Extrapkg_Contacter_Model_Title extends Varien_Object
{
    const MR	= 1;
    const MRS	= 2;
    const MSS	= 3;

    static public function getOptionArray()
    {
        return array(
            self::MR    => Mage::helper('contacter')->__('Mr.'),
            self::MRS   => Mage::helper('contacter')->__('Mrs.')
            self::MSS   => Mage::helper('contacter')->__('Miss')
        );
    }
}