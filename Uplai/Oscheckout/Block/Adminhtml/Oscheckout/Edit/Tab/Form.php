<?php

class Uplai_Oscheckout_Block_Adminhtml_Oscheckout_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('oscheckout_form', array('legend'=>Mage::helper('oscheckout')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('oscheckout')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('oscheckout')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('oscheckout')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('oscheckout')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('oscheckout')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('oscheckout')->__('Content'),
          'title'     => Mage::helper('oscheckout')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getOscheckoutData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getOscheckoutData());
          Mage::getSingleton('adminhtml/session')->setOscheckoutData(null);
      } elseif ( Mage::registry('oscheckout_data') ) {
          $form->setValues(Mage::registry('oscheckout_data')->getData());
      }
      return parent::_prepareForm();
  }
}