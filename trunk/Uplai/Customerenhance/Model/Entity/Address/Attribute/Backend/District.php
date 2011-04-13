<?php
/**
 * Address region attribute backend
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Uplai_Customerenhance_Model_Entity_Address_Attribute_Backend_Region extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    public function beforeSave($object)
    {
        $district = $object->getData('district');
        if (is_numeric($district)) {
            $regionModel = Mage::getModel('directory/region')->load($region);
            if ($regionModel->getId() && $object->getCountryId() == $regionModel->getCountryId()) {
                $object->setRegionId($regionModel->getId())
                    ->setRegion($regionModel->getName());
            }
        }
        return $this;
    }
}
