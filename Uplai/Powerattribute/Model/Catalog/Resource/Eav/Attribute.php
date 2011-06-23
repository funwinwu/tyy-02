<?php
/**
 * Catalog attribute model
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Uplai_Powerattribute_Model_Catalog_Resource_Eav_Attribute extends Mage_Catalog_Model_Resource_Eav_Attribute
{
	/**
     * Processing object after save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave()
    {
        //print_r($_REQUEST);
        //exit();
        return parent::_afterSave();
    }

}
