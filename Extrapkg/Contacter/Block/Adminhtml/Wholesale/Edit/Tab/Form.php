<?php

class Extrapkg_Contacter_Block_Adminhtml_Wholesale_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('contacter_form', array('legend'=>Mage::helper('contacter')->__('Item information')));
     
//      $fieldset->addField('title', 'select', array(
//          'label'     => Mage::helper('contacter')->__('Title'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'title',
//          'values'    => array(
//              array(
//                  'value'     => 1,
//                  'label'     => Mage::helper('contacter')->__('Mr.'),
//              ),
//              array(
//                  'value'     => 2,
//                  'label'     => Mage::helper('contacter')->__('Mrs.'),
//              ),              
//              array(
//                  'value'     => 3,
//                  'label'     => Mage::helper('contacter')->__('Miss'),
//              ),
//          ),
//      ));
      
      $fieldset->addField('first_name', 'text', array(
          'label'     => Mage::helper('contacter')->__('Inquired Product(s)'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'first_name',
      ));
      
      $fieldset->addField('tel', 'text', array(
          'label'     => Mage::helper('contacter')->__('Quantity'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'tel',
      ));
      
      $fieldset->addField('conpany_name', 'text', array(
          'label'     => Mage::helper('contacter')->__('Shipping Address'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'conpany_name',
      ));
      
       $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('contacter')->__('Contact Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'email',
      ));
      
      
//      $fieldset->addField('conpany_address_01', 'text', array(
//          'label'     => Mage::helper('contacter')->__('Address 1'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'conpany_address_01',
//      ));
//      
//      $fieldset->addField('conpany_address_02', 'text', array(
//          'label'     => Mage::helper('contacter')->__('Address 2'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'conpany_address_02',
//      ));
          
//      $countries = Mage::getModel('adminhtml/system_config_source_country')
//       ->toOptionArray();
//       unset($countries[0]);
//       $countryId = Mage::getStoreConfig('general/country/default');
//      $fieldset->addField('country', 'select', array(
//                'name' => 'country',
//                'label' => Mage::helper('contacter')->__('Country'),
//                'title' => Mage::helper('contacter')->__('Please select Country'),
//                'class' => 'required-entry',
//                'required' => true,
//                'values' => $countries,
//                'value' => $countryId,
//            ));

//      $fieldset->addField('business_nuture', 'select', array(
//          'label'     => Mage::helper('contacter')->__('Request'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'business_nuture',
//          'values'    => array(
//              array(
//                  'value'     => 1,
//                  'label'     => Mage::helper('contacter')->__('Replacement'),
//              ),
//              array(
//                  'value'     => 2,
//                  'label'     => Mage::helper('contacter')->__('Refund'),
//              ),              
//          ),
//      ));
      
//      $fieldset->addField('subject', array(
//          'label'    => Mage::helper('contacter')->__('Reasons for the RMA'),
//          'class'     => 'required-entry',
//          'required'  => true,
//          'name'      => 'subject',
//          'values'   => array(
//             array(
//                  'value'     => 1,
//                  'label'     => Mage::helper('contacter')->__('Defective'),
//              ),
//             array(
//                  'value'     => 2,
//                  'label'     => Mage::helper('contacter')->__('Incompatible'),
//              ),
//             array(
//                  'value'     => 3,
//                  'label'     => Mage::helper('contacter')->__('Wrong item ordered'),
//              ),
//          	 array(
//                  'value'     => 4,
//                  'label'     => Mage::helper('contacter')->__('Shipping problems'),
//              ),
//          	 array(
//                  'value'     => 5,
//                  'label'     => Mage::helper('contacter')->__('Other'),
//              ),
//          ),
//      ));
      
//      $fieldset->addField('business_year', 'text', array(
//          'label'     => Mage::helper('contacter')->__('Years in business'),
//          'name'      => 'business_year',
//      ));
//      
//      $fieldset->addField('business_role', 'text', array(
//          'label'     => Mage::helper('contacter')->__('Role in business'),
//          'name'      => 'business_role',
//      ));
/*
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('contacter')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
*/	  
		
//      $fieldset->addField('subject', 'text', array(
//          'label'     => Mage::helper('contacter')->__('Message subject'),
//          'name'      => 'subject',
//      ));
     
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