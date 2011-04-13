<?php

class Extrapkg_Contacter_Model_Contacter extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('contacter/contacter');
    }
    
    public function getOfferQty( $product_id )
    {
    	$session = Mage::getSingleton('catalog/session');
    	$products_make_offer = $session->getData( "products_make_offer" );
    	
    	foreach( $products_make_offer['product_id'] as $index => $_product_id ){
    		if( $product_id == $_product_id ){
    			return $products_make_offer['offer_qty'][ $index ];
    		}
    	}
    	return 0;
    }
    
    public function getOfferPrice( $product_id )
    {
    	$session = Mage::getSingleton('catalog/session');
    	$products_make_offer = $session->getData( "products_make_offer" );
    	
    	foreach( $products_make_offer['product_id'] as $index => $_product_id ){
    		if( $product_id == $_product_id ){
    			return $products_make_offer['offer_price'][ $index ];
    		}
    	}
    	return 0;
    }
    
    public function clearProduct()
    {
    	$session = Mage::getSingleton('catalog/session');
    	$session->unsetData( "products_make_offer" );
    	return ;
    }
    
    public function getCurrency()
    {
    	$currenty = Mage::app()->getLayout()->getBlock("currency");
    	if( !$currenty ){
    		$currenty = Mage::app()->getLayout()->createBlock("directory/currency");
    	}
    	return $currenty;
    }
}