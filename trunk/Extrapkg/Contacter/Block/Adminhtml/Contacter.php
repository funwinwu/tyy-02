<?php
class Extrapkg_Contacter_Block_Adminhtml_Contacter extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_contacter';
    $this->_blockGroup = 'contacter';
    $this->_headerText = Mage::helper('contacter')->__('List of product enquiry');   
    //parent::__construct();
    $this->setTemplate('widget/grid/container.phtml');
  }
}