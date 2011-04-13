<?php
class Uplai_Repay_Block_Repay extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRepay()     
     { 
        if (!$this->hasData('repay')) {
            $this->setData('repay', Mage::registry('repay'));
        }
        return $this->getData('repay');
        
    }
}