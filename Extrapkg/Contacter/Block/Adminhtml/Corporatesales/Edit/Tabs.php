<?php

class Extrapkg_Contacter_Block_Adminhtml_Corporatesales_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('Corporatesales_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('contacter')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('contacter')->__('Item Information'),
          'title'     => Mage::helper('contacter')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('contacter/adminhtml_corporatesales_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}