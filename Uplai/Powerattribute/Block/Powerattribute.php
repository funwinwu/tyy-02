<?php
class Uplai_Powerattribute_Block_Powerattribute extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPowerattribute()     
     { 
        if (!$this->hasData('powerattribute')) {
            $this->setData('powerattribute', Mage::registry('powerattribute'));
        }
        return $this->getData('powerattribute');
        
    }
}