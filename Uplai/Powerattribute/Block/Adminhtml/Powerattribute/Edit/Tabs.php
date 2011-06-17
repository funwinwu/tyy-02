<?php

class Uplai_Powerattribute_Block_Adminhtml_Powerattribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('powerattribute_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('powerattribute')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('powerattribute')->__('Item Information'),
          'title'     => Mage::helper('powerattribute')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('powerattribute/adminhtml_powerattribute_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}