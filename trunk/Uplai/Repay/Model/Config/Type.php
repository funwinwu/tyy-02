<?php

class Uplai_Repay_Model_Config_Type extends Varien_Object
{
    const TYPE_HTML	= 1;
    const TYPE_POP = 2;

    static public function getOptionArray()
    {
        return array(
            self::TYPE_HTML    => Mage::helper('repay')->__('Messge Inside Html'),
            self::TYPE_POP   => Mage::helper('repay')->__('Javascript Pop')
        );
    }
	
	public function toOptionArray()
    {
        return array(
            array('value'=>self::TYPE_HTML,'label'=>Mage::helper('repay')->__('Messge Inside Page')),
            array('value'=>self::TYPE_POP, 'label'=>Mage::helper('repay')->__('Javascript Pop')),
        );
    }
}