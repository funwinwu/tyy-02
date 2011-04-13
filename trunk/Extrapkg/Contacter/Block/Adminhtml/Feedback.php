<?php
class Extrapkg_Contacter_Block_Adminhtml_Feedback extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_feedback';
    $this->_blockGroup = 'contacter';
    $this->_headerText = Mage::helper('contacter')->__('List of feedback & comments');
    //$this->_addButtonLabel = Mage::helper('contacter')->__('Add Item');
	
    //parent::__construct();
    $this->setTemplate('widget/grid/container.phtml');
  }
}