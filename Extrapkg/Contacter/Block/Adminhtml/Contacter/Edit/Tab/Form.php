<?php

class Extrapkg_Contacter_Block_Adminhtml_Contacter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('contacter_form', array('legend'=>Mage::helper('contacter')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('contacter')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
/*
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('contacter')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
*/	  
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('contacter')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('contacter')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('contacter')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('contacter')->__('Content'),
          'title'     => Mage::helper('contacter')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getContacterData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getContacterData());
          Mage::getSingleton('adminhtml/session')->setContacterData(null);
      } elseif ( Mage::registry('contacter_data') ) {
          $form->setValues(Mage::registry('contacter_data')->getData());
      }
      return parent::_prepareForm();
  }
}