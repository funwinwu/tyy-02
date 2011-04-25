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
class Uplai_Repay_Onepage2Controller extends Mage_Checkout_OnepageController
{  

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
		Mage::getSingleton('checkout/session')->clear(); 
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
