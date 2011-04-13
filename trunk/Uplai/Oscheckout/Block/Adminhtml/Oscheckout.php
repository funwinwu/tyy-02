<?php
class Uplai_Oscheckout_Block_Adminhtml_Oscheckout extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_oscheckout';
    $this->_blockGroup = 'oscheckout';
    $this->_headerText = Mage::helper('oscheckout')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('oscheckout')->__('Add Item');
    parent::__construct();
  }
}