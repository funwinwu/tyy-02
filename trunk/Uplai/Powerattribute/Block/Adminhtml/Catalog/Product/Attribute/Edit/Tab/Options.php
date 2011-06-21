<?php
class Uplai_Powerattribute_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tab_Options extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('powerattribute/catalog/product/attribute/options.phtml');
    }
}