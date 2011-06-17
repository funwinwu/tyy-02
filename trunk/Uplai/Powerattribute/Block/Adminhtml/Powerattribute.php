<?php
class Uplai_Powerattribute_Block_Adminhtml_Powerattribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_powerattribute';
    $this->_blockGroup = 'powerattribute';
    $this->_headerText = Mage::helper('powerattribute')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('powerattribute')->__('Add Item');
    parent::__construct();
  }
}