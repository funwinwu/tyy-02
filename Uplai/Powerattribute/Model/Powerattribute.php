<?php

class Uplai_Powerattribute_Model_Powerattribute extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('powerattribute/powerattribute');
    }
    
    public function getPowerOption( $option_id )
    {
    	$collection =  Mage::getModel("powerattribute/powerattribute")->getCollection()->addFieldToFilter('option_id',$option_id);
    	if( $collection->getSize() ){
    		foreach ( $collection as $_option ){
    			return $_option;
    		}
    	}
    	return '';
    }
}