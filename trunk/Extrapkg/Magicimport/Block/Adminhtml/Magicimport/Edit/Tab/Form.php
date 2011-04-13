<?php

class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('magicimport_form', array('legend'=>Mage::helper('magicimport')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('magicimport')->__('Title'),          
          'required'  => false,
          'name'      => 'title',
      ));
	 
	   $fieldset->addField('import_type', 'select', array(
          'label'     => Mage::helper('magicimport')->__('Action type'),          
          'required'  => true,
		  'values'     => Extrapkg_Magicimport_Model_Importtype::getOptionArray(),
          'name'      => 'import_type',
	  ));
 /*	  
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
	  
	  $fieldset->addField('set', 'text', array(
          'label'     => Mage::helper('magicimport')->__('Attribute set'),          
          'required'  => false,
		  'value'     => '9',
          'name'      => 'type',
	  ));
*/

	/**
         * Check is single store mode
         */
        $fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
       
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('magicimport')->__('File'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'filename',
	  ));
	  
	 
		$fieldset->addField('data_type', 'select', array(
          'label'     => Mage::helper('magicimport')->__('To update what?'),
          'name'      => 'data_type',
          'values'    => Extrapkg_Magicimport_Model_Datatype::getOptionArray(),          
      ));
      $fieldset->addField('main_status', 'select', array(
          'label'     => Mage::helper('magicimport')->__('Status'),
          'name'      => 'main_status',
          'values'    => Extrapkg_Magicimport_Model_Datastatus::getOptionArray(),
         
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('magicimport')->__('Comment'),
          'title'     => Mage::helper('magicimport')->__('Comment'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getMagicimportData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMagicimportData());
          Mage::getSingleton('adminhtml/session')->setMagicimportData(null);
      } elseif ( Mage::registry('magicimport_data') ) {
          $form->setValues(Mage::registry('magicimport_data')->getData());
      }
      return parent::_prepareForm();
  }
}