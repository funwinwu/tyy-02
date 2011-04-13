<?php
/**
 * Onestep checkout block
 *
 * @category   Oscheckout
 * @package    Oscheckout
 * @author      Ken Chen <tomyoyo@gmail.com>
 */
class Uplai_Oscheckout_Block_Onestep_Shipping extends Uplai_Oscheckout_Block_Onestep_Abstract
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('shipping', array(
            'label'     => Mage::helper('oscheckout')->__('Shipping Information'),
            'is_show'   => $this->isShow()
        ));
		if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('shipping', 'allow', true);
        }
        parent::_construct();
    }

    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }

    public function getAddress()
    {
        if (!$this->isCustomerLoggedIn()) {
            return $this->getQuote()->getShippingAddress();
        } else {
            return Mage::getModel('sales/quote_address');
        }
    }

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();
    }
}
