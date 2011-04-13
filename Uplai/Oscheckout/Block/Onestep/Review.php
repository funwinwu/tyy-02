<?php

class Uplai_Oscheckout_Block_Onestep_Review extends Uplai_Oscheckout_Block_Onestep_Abstract
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('review', array(
            'label'     => Mage::helper('oscheckout')->__('Order Review'),
            'is_show'   => $this->isShow()
        ));
        parent::_construct();

        $this->getQuote()->collectTotals()->save();
    }
}
