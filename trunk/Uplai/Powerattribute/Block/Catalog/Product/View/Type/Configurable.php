<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog super product configurable part block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Uplai_Powerattribute_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{   
    //add by ken.
    public function getAttributeOptions( $current_attribute )
    {
	    $attributes = array();
        $options = array();
        $store = Mage::app()->getStore();
        foreach ($this->getAllowProducts() as $product) {
            $productId  = $product->getId();

            foreach ($this->getAllowAttributes() as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                if (!isset($options[$productAttribute->getId()])) {
                    $options[$productAttribute->getId()] = array();
                }

                if (!isset($options[$productAttribute->getId()][$attributeValue])) {
                    $options[$productAttribute->getId()][$attributeValue] = array();
                }
                $options[$productAttribute->getId()][$attributeValue][] = $productId;
            }
        }

        $this->_resPrices = array(
            $this->_preparePrice($this->getProduct()->getFinalPrice())
        );
    	
    	$productAttribute = $current_attribute->getProductAttribute();
        $attributeId = $productAttribute->getId();
        $info = array(
           'id'        => $productAttribute->getId(),
           'code'      => $productAttribute->getAttributeCode(),
           'label'     => $current_attribute->getLabel(),
           'options'   => array()
        );

        $optionPrices = array();
        $prices = $current_attribute->getPrices();
        if (is_array($prices)) {
            foreach ($prices as $value) {
                if(!$this->_validateAttributeValue($attributeId, $value, $options)) {
                    continue;
                }

                $info['options'][] = array(
                    'id'    => $value['value_index'],
                    'label' => $value['label'],
                    'price' => $this->_preparePrice($value['pricing_value'], $value['is_percent']),
                    'products'   => isset($options[$attributeId][$value['value_index']]) ? $options[$attributeId][$value['value_index']] : array(),
                );
                $optionPrices[] = $this->_preparePrice($value['pricing_value'], $value['is_percent']);
                //$this->_registerAdditionalJsPrice($value['pricing_value'], $value['is_percent']);
            }
        }
        /**
         * Prepare formated values for options choose
         */
        foreach ($optionPrices as $optionPrice) {
            foreach ($optionPrices as $additional) {
                $this->_preparePrice(abs($additional-$optionPrice));
            }
        }
        if($this->_validateAttributeInfo($info)) {
           $attributes[$attributeId] = $info;
        }
        return $attributes;
    }
    
    /*return base image of given product*/
    public function getProductImage( $product )
    {
    	
    }
    /*get Default images for configurable product.
    it will return images from child product.
    the product with most images will be selected.
    */
    public function getProductDefaultImages()
    {
    	 
    	foreach ($this->getAllowProducts() as $product) {
            $product->getMediaGalleryImages();
        }
    }
}
