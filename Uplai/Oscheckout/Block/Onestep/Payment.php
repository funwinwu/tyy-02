<?php
/**
 * Onestep checkout block
 *
 * @category   Oscheckout
 * @package    Oscheckout
 * @author      Ken Chen <tomyoyo@gmail.com>
 */
class Uplai_Oscheckout_Block_Onestep_Payment extends Uplai_Oscheckout_Block_Onestep_Abstract
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('payment', array(
            'label'     => $this->__('Payment Information'),
            'is_show'   => $this->isShow()
        ));
        parent::_construct();
    }

    /**
     * Getter
     *
     * @return float
     */
    public function getQuoteBaseGrandTotal()
    {
        return (float)$this->getQuote()->getBaseGrandTotal();
    }
}
