<?php

class Extrapkg_Contacter_Block_Adminhtml_Corporatesales_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('contacter_form', array('legend'=>Mage::helper('contacter')->__('Item information')));
     
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
      
      $fieldset->addField('address', 'text', array(
          'label'     => Mage::helper('contacter')->__('Address'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'address',
      ));
      
      $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('contacter')->__('City'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'city',
      ));
      
       $fieldset->addField('conpany_name', 'text', array(
          'label'     => Mage::helper('contacter')->__('Company Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'conpany_name',
      ));
      
       $fieldset->addField('no_of_employees', 'text', array(
          'label'     => Mage::helper('contacter')->__('No. of employees'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'no_of_employees',
      ));
      
       $fieldset->addField('position', 'text', array(
          'label'     => Mage::helper('contacter')->__('Position'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'position',
      ));
      
      $fieldset->addField('cif', 'text', array(
          'label'     => Mage::helper('contacter')->__('CIF'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cif',
      ));
      
      
      $countries = Mage::getModel('adminhtml/system_config_source_country')
       ->toOptionArray();
       unset($countries[0]);
       $countryId = Mage::getStoreConfig('general/country/default');
      $fieldset->addField('country', 'select', array(
                'name' => 'country',
                'label' => Mage::helper('contacter')->__('Country'),
                'title' => Mage::helper('contacter')->__('Please select Country'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $countries,
                'value' => $countryId,
            ));
            
	$fieldset->addField('gift_feedback', 'text', array(
          'label'     => Mage::helper('contacter')->__('Gift need feedback.'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'gift_feedback',
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