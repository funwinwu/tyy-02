<?php

class Uplai_Repay_Helper_Checkout extends Mage_Core_Helper_Abstract
{
	public function getCheckoutUrl( $params = null )
	{
		return $this->_getUrl('repay/checkout/index',$params);
	}
	
	public function canCheckout( $order )
	{
		return (int)$order->getTotalDue()>0 || ( $order->getState()== 'pending_payment' );
	}
	
	public function enabled()
	{
		
		return Mage::getStoreConfig("checkout/repay/active");
	}
	
	public function getOrderCreateDesc($order)
	{
		$html = '';
		if( Mage::getStoreConfig('checkout/repay/method') == Uplai_Repay_Model_Config_Type::TYPE_HTML ){
			$html = $this->__( Mage::getStoreConfig("checkout/repay/desc"),$order->getIncrementId() ) ;
		}else if( Mage::getStoreConfig('checkout/repay/method') == Uplai_Repay_Model_Config_Type::TYPE_POP ){
			$html = '<script type="text/javascript">alert("'.$this->__( Mage::getStoreConfig("checkout/repay/desc"),$order->getIncrementId() ).'")</script>' ;
		}
		return $html;
	}
}