<?php
class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit_Tab_Importpannel_Pannel extends Mage_Adminhtml_Block_Template
{
	 public function __construct()
    {
        $this->setTemplate('magicimport/importpannel/pannel.phtml');
    }
	
	public function getMagicimport()
	{
		return Mage::registry('magicimport_data');
	}
}