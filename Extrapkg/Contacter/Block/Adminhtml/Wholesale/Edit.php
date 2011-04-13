<?php

class Extrapkg_Contacter_Block_Adminhtml_Wholesale_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'contacter';
        $this->_controller = 'adminhtml_wholesale';
        
        $this->_updateButton('save', 'label', Mage::helper('contacter')->__('Save this wholesale'));
        $this->_updateButton('delete', 'label', Mage::helper('contacter')->__('Delete this wholesale'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('contacter_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'contacter_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'contacter_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('contacter_data') && Mage::registry('contacter_data')->getId() ) {
            return Mage::helper('contacter')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('contacter_data')->getTitle()));
        } else {
            return Mage::helper('contacter')->__('Add Item');
        }
    }
}
?>