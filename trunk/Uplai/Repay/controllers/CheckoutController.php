<?php
class Uplai_Repay_CheckoutController extends Mage_Core_Controller_Front_Action
{
    private function setOrder()
    {
    	$order_id = $this->getRequest()->getParam('order_id');    	
    	!$order_id && $order_id = $this->getRequest()->getPost('order_id');
    	if ($order_id ) {    		
    		$order = Mage::getModel( "sales/order" )->load($order_id);    		    		
    		Mage::getSingleton('checkout/session')->setOrder( $order );
    	}
    	return;
    }
	
	public function indexAction()
    {
    	$this->setOrder();
    	
    	if( $head = $this->getLayout()->getBlock('head') ){
    		$head->setTitle(Mage::helper('repay')->__('Payment'));
    	}
    	
    	if( $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs') ){
	    	$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('repay')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl()));
	        $breadcrumbs->addCrumb('repay-payment', array('label'=>Mage::helper('repay')->__('Payment'), 'title'=>Mage::helper('repay')->__('Payment')));
    	}
	    $this->loadLayout();     
		$this->renderLayout();
    }
    
    public function savePaymentAction()
    {
    	ob_start();
    	$this->setOrder();
    	$order = Mage::getSingleton('checkout/session')->getOrder();
    	echo $order->getIncrementId();
    	exit();
    	Mage::getSingleton('checkout/session')->setLastRealOrderId( $order->getIncrementId() );
    	if ($data = $this->getRequest()->getPost('payment', false)) {
    		$payment = Mage::getModel("repay/order_payment");
    		$payment->setOrder( $order )->importData( $data );
    		//$redirect = $payment->getOrderPlaceRedirectUrl();
    	//echo $redirect;
    		$old_payment = $order->getPayment();
	    	$data = $payment->getData();
	    	unset($data['method_instance']);
	    	foreach( $data as $key => $var ){
	    		$old_payment->setData( $key,$var );
	    	}
	    	$old_payment->save();
    	}
    	
    	$payment = $order->getPayment();
    	$redirect = '';
    	 
    	if( $payment->canCapture() ){
    		try{
    			$payment->capture();
    			$result['success'] = true;
         		$result['error']   = false;
         		//$this->_getSession()->addSuccess($this->__('Capture successfull'));
    		}catch( Exception $e ){
    			Mage::logException($e);
	            Mage::helper('checkout')->sendPaymentFailedEmail($order, $e->getMessage());
	            $result['success']  = false;
	            $result['error']    = true;
	            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
	            $this->_getSession()->addError($result['error_messages']);
	            $this->_redirect(Mage::helper("repay/checkout")->getCheckoutUrl(array( 'order_id'=>$order->getId() )));
    		}
    	}
    	
    	//get redirect 
    	$payment = Mage::getModel("repay/order_payment");
    	$payment->setOrder( $order )->importData( $order->getPayment()->getData() );    	
    	$redirect = $payment->getOrderPlaceRedirectUrl();
    	
    	if( $redirect ){
    		$result['redirect'] = $redirect;
    		$redirect = str_replace( Mage::getBaseUrl('web'),'',$redirect );
    		
    		$this->_redirect($redirect);    		
    	}else{
    		$this->_redirect('repay/checkout/success');
    	}
    	//$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    public function successAction()
    {
    	$this->loadLayout();     
		$this->renderLayout();
    }
}