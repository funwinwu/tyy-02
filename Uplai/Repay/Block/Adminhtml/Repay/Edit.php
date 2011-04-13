<?php

class Uplai_Repay_Block_Adminhtml_Repay_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'repay';
        $this->_controller = 'adminhtml_repay';
        
        $this->_updateButton('save', 'label', Mage::helper('repay')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('repay')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('repay_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'repay_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'repay_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('repay_data') && Mage::registry('repay_data')->getId() ) {
            return Mage::helper('repay')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('repay_data')->getTitle()));
        } else {
            return Mage::helper('repay')->__('Add Item');
        }
    }
}