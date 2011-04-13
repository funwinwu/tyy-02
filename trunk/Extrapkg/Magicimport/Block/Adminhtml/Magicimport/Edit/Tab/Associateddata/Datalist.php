<?php
class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit_Tab_Associateddata_Datalist extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
  {
    $this->_controller = 'adminhtml_magicimport_edit_tab_associateddata_magicdata';
    $this->_blockGroup = 'magicimport';
    parent::__construct();
  }
}