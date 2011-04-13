<?php
class Extrapkg_Magicimport_Block_Adminhtml_Magicimport extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_magicimport';
    $this->_blockGroup = 'magicimport';
    $this->_headerText = Mage::helper('magicimport')->__('Import Manager');
    $this->_addButtonLabel = Mage::helper('magicimport')->__('Add Import');
    parent::__construct();
  }
}