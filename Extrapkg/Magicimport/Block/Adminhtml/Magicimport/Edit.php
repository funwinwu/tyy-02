<?php

class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'magicimport';
        $this->_controller = 'adminhtml_magicimport';
        
        $this->_updateButton('save', 'label', Mage::helper('magicimport')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('magicimport')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

		$id = Mage::app()->getRequest()->getParam('id');
		if( $id ){
				$this->_addButton('getdatafromxls', array(
						'label'     => Mage::helper('magicimport')->__('Get Data From XLS File'),
						'onclick'   => 'setLocation(\''.$this->getUrl("*/*/getDataFromXLS",array('id'=>$id)).'\')',
						'class'     => 'save',
				), -100);

		}
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('magicimport_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'magicimport_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'magicimport_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('magicimport_data') && Mage::registry('magicimport_data')->getId() ) {
            return Mage::helper('magicimport')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('magicimport_data')->getTitle()));
        } else {
            return Mage::helper('magicimport')->__('New import');
        }
    }
}