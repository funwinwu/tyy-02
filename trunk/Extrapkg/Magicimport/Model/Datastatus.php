<?php

class Extrapkg_Magicimport_Model_Datastatus extends Varien_Object
{
    const TYPE_PENDDING	= 'p';
    const TYPE_FINISH	= 'f';
    const TYPE_CANCELED	= 'c';
    const TYPE_RESTORE	= 'r';
    

    static public function getOptionArray()
    {
        return array(
            self::TYPE_PENDDING    => Mage::helper('magicimport')->__('Pennding'),
            self::TYPE_FINISH   => Mage::helper('magicimport')->__('Finish (imported or updated)'),           
            self::TYPE_CANCELED   => Mage::helper('magicimport')->__('Canceled.There will be some mistaked inside data.'),           
            self::TYPE_RESTORE   => Mage::helper('magicimport')->__('Had been restored.'),           
        );
    }
}