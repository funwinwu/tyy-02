<?php
class Extrapkg_Contacter_Block_Adminhtml_Opportunities extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_opportunities';
    $this->_blockGroup = 'contacter';
    $this->_headerText = Mage::helper('contacter')->__('List of business opportunities');
    //$this->_addButtonLabel = Mage::helper('contacter')->__('Add Item');
	
    //parent::__construct();
    $this->setTemplate('widget/grid/container.phtml');
  }
}