<?php

class Uplai_Powerattribute_Block_Adminhtml_Powerattribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'powerattribute';
        $this->_controller = 'adminhtml_powerattribute';
        
        $this->_updateButton('save', 'label', Mage::helper('powerattribute')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('powerattribute')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('powerattribute_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'powerattribute_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'powerattribute_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('powerattribute_data') && Mage::registry('powerattribute_data')->getId() ) {
            return Mage::helper('powerattribute')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('powerattribute_data')->getTitle()));
        } else {
            return Mage::helper('powerattribute')->__('Add Item');
        }
    }
}