<?php

class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('magicimport_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('magicimport')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('magicimport')->__('Item Information'),
          'title'     => Mage::helper('magicimport')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tab_form')->toHtml(),
      ));
	  
      if( Mage::registry('magicimport_data')->getId() ){
		  $this->addTab('data_section', array(
					'label'     => Mage::helper('poll')->__('Associated data'),
					'title'     => Mage::helper('poll')->__('Associated data'),
					'content'   => $this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tab_associateddata')
									->append($this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tab_associateddata_datalist'))
									->toHtml(),
					'active'    => ( $this->getRequest()->getParam('tab') == 'data_section' ) ? true : false,
				));
			$this->addTab('action_section', array(
				'label'     => Mage::helper('poll')->__('Import pannel'),
				'title'     => Mage::helper('poll')->__('Import pannel'),
				'content'   => $this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tab_importpannel')
								->append($this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tab_importpannel_pannel'))
								->toHtml(),
				'active'    => ( $this->getRequest()->getParam('tab') == 'data_section' ) ? true : false,
			));
		}
		 
      return parent::_beforeToHtml();
  }
}