<?php
class Extrapkg_Magicimport_Block_Magicimport extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getMagicimport()     
     { 
        if (!$this->hasData('magicimport')) {
            $this->setData('magicimport', Mage::registry('magicimport'));
        }
        return $this->getData('magicimport');
        
    }
}