<?php

class Extrapkg_Contacter_Block_Adminhtml_Feedback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('contacter_form', array('legend'=>Mage::helper('contacter')->__('Feedback/comments')));
     
      $fieldset->addField('title', 'select', array(
          'label'     => Mage::helper('contacter')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('contacter')->__('Mr.'),
              ),
              array(
                  'value'     => 2,
                  'label'     => Mage::helper('contacter')->__('Mrs.'),
              ),              
              array(
                  'value'     => 3,
                  'label'     => Mage::helper('contacter')->__('Miss'),
              ),
          ),
      ));
      
      $fieldset->addField('first_name', 'text', array(
          'label'     => Mage::helper('contacter')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'first_name',
      ));
      
       $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('contacter')->__('Contact Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'email',
      ));
      
       $fieldset->addField('tel', 'text', array(
          'label'     => Mage::helper('contacter')->__('Contact TEL'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'tel',
      ));
      
     
		
      $fieldset->addField('subject', 'text', array(
          'label'     => Mage::helper('contacter')->__('Message subject'),
          'name'      => 'subject',
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