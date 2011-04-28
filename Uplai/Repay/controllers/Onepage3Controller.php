<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */



require_once 'Mage/Checkout/controllers/OnepageController.php';
class Uplai_Repay_Onepage3Controller extends Mage_Checkout_OnepageController
{    
    /**
     * Checkout page
     */
    public function indexAction()
    {
        if( Mage::getSingleton('checkout/session')->getLastRealOrderId() ){
        	Mage::getSingleton('checkout/session')->setLastRealOrderId(null);
        }
       
    	if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }
    
     /**
     * Save payment ajax action
     *
     * Sets either redirect or a JSON response
     */
    public function savePaymentAction()
    {
		if( !Mage::helper('repay/checkout')->enabled() )
			return parent::savePaymentAction();
		
		
		if ($this->_expireAjax()) {
            return;
        }
        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            // set payment to quote
            $result = array();
            $data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnepage()->savePayment($data);
            if( !Mage::getSingleton('checkout/session')->getLastRealOrderId() ){
				$this->saveOrder();
            }
			$order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => '<div class="order-tips">'.Mage::Helper('repay/checkout')->getOrderCreateDesc($order).'</div>'.$this->_getReviewHtml()
                );
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
			
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
	
	public function saveOrder()
    {
        
        $result = array();
        try {
            
            if ($data = $this->getRequest()->getPost('payment', array())) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            $this->getOnepage()->saveOrder();
            
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
        }       
    }
    
    /**
     * Create order action
     */
    public function saveOrderAction()
    {
        if( !Mage::helper('repay/checkout')->enabled() )
			parent::saveOrderAction();
		
		if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }
            if ($data = $this->getRequest()->getPost('payment', false)) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            //repair order
            //$this->repairOrder();
            //print_r( $this->getOnepage()->getQuote()->getShippingAddress()->getData() );
            //exit();
            //$this->getOnepage()->saveOrder();
			
			//Mage::getSingleton('checkout/session')->setLastRealOrderId(null);
            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                $result['goto_section'] = $gotoSection;
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }

            if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }
		//add by ken .to clear cart.
		foreach( $this->getOnepage()->getQuote()->getItemsCollection() as $item ){
			Mage::getSingleton('checkout/cart')->removeItem( $item->getId() )->save();
		}
		Mage::getSingleton('checkout/cart')->getQuote()->collectTotals()
                ->save();
		
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    private function repairOrder()
    {
    	$order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
    	//shipping addressw
    	$quote_address = $this->getOnepage()->getQuote()->getShippingAddress()->getData();
    	$order_address = $order->getShippingAddress();
    	foreach( $order_address->getData() as $key => $var ){
    		$order_address->setData( $key,$quote_address[$key] );
    	}
    	$order_address->save();
    	
    	//billing address
    	$quote_address = $this->getOnepage()->getQuote()->getBillingAddress()->getData();
    	$order_address = $order->getBillingAddress();    	
    	foreach( $order_address->getData() as $key => $var ){
    		$order_address->setData( $key,$quote_address[$key] );
    	}
    	$order_address->save();
    	
    	//shipping method
    	//print_r( $this->getOnepage()->getQuote()->getShippingAddress()->getData() );
    	print_r( $order->getShippingMethod()->getData() );
    	exit();
    }
}
