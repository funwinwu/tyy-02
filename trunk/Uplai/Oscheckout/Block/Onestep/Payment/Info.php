<?php
/**
 * Onestep checkout block
 *
 * @category   Oscheckout
 * @package    Oscheckout
 * @author      Ken Chen <tomyoyo@gmail.com>
 */
class Uplai_Oscheckout_Block_Onestep_Info extends Mage_Payment_Block_Info_Container
{
    /**
     * Retrieve payment info model
     *
     * @return Mage_Payment_Model_Info
     */
    public function getPaymentInfo()
    {
        $info = Mage::getSingleton('checkout/session')->getQuote()->getPayment();
        if ($info->getMethod()) {
            return $info;
        }
        return false;
    }

    protected function _toHtml()
    {
        $html = '';
        if ($block = $this->getChild($this->_getInfoBlockName())) {
            $html = $block->toHtml();
        }
        return $html;
    }
}
