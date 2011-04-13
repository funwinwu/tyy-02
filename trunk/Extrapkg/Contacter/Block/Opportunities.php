<?php
class Extrapkg_Contacter_Block_Opportunities extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getContacter()     
     { 
        if (!$this->hasData('contacter')) {
            $this->setData('contacter', Mage::registry('contacter'));
        }
        return $this->getData('contacter');
        
    }
}