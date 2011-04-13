<?php

class Uplai_Repay_Block_Adminhtml_Repay_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('repay_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('repay')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('repay')->__('Item Information'),
          'title'     => Mage::helper('repay')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('repay/adminhtml_repay_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}