<?php
class Uplai_Repay_Block_Adminhtml_Repay extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_repay';
    $this->_blockGroup = 'repay';
    $this->_headerText = Mage::helper('repay')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('repay')->__('Add Item');
    parent::__construct();
  }
}