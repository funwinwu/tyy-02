<?php

class Uplai_Oscheckout_Block_Adminhtml_Oscheckout_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'oscheckout';
        $this->_controller = 'adminhtml_oscheckout';
        
        $this->_updateButton('save', 'label', Mage::helper('oscheckout')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('oscheckout')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('oscheckout_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'oscheckout_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'oscheckout_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('oscheckout_data') && Mage::registry('oscheckout_data')->getId() ) {
            return Mage::helper('oscheckout')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('oscheckout_data')->getTitle()));
        } else {
            return Mage::helper('oscheckout')->__('Add Item');
        }
    }
}