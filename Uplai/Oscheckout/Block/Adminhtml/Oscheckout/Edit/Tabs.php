<?php

class Uplai_Oscheckout_Block_Adminhtml_Oscheckout_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('oscheckout_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('oscheckout')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('oscheckout')->__('Item Information'),
          'title'     => Mage::helper('oscheckout')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('oscheckout/adminhtml_oscheckout_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}