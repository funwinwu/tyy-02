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
    
    public function getDefaultProduct()
    {    	
    	static $default_product;
    	if( empty($default_product) ){
	    	$pre_count = 0;    	
	    	foreach ($this->getAllowProducts() as $_product) {
	    		$_product->load();
	            if( $_product->getMediaGalleryImages()->getSize() > $pre_count){
		            $default_product = $_product;
		            $pre_count = $_product->getMediaGalleryImages()->getSize();
	            }
	        }
    	}
        return  $default_product;        
    }
    
    /*get Default images for configurable product.
    it will return images from child product.
    the product with most images will be selected.
    */
    public function getDefaultProductImage($type,$resize_w,$resize_h=0)
    {    	
    	!$resize_h && $resize_h = $resize_w;
        return  Mage::helper('catalog/image')->init($this->getDefaultProduct(), $type)->resize($resize_w,$resize_h);        
    }
    
    /*get Default images for configurable product.
    it will return images from child product.
    the product with most images will be selected.
    */
    public function getDefaultProductGallery()
    {   	
    	return $this->getDefaultProduct()->getMediaGalleryImages();
    }
    
    public function getChildProductImages($type,$resize_w,$resize_h=0)
    {    	
    	!$resize_h && $resize_h = $resize_w;
    	$images = array();
    	foreach ($this->getAllowProducts() as $_product) {
    		$images[$_product->getId()]['resize'] = Mage::helper('catalog/image')->init($_product, $type)->resize($resize_w,$resize_h)->__toString(); 
    		$images[$_product->getId()]['org'] = Mage::helper('catalog/image')->init($_product, $type)->__toString(); 
        }        
    	return $images;
    }
    
    public function getJsonPowerConfig()
    {
        $attributes = array();
        $attributesoptions = array();
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

        foreach ($this->getAllowAttributes() as $attribute) {
            $productAttribute = $attribute->getProductAttribute();
            $attributeId = $productAttribute->getId();
            $info = array(
               'id'        => $productAttribute->getId(),
               'code'      => $productAttribute->getAttributeCode(),
               'label'     => $attribute->getLabel(),
               'options'   => array()
            );

            $optionPrices = array();
            $prices = $attribute->getPrices();
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
               foreach( $info['options'] as $_info ){
               		if( $_info['id'] > 0 )
              			$attributesoptions['_'.$attributeId.'_'.$_info['id']] = $_info;
            	}	
            }
        }
        
        /*echo '<pre>';
        print_r($this->_prices);
        echo '</pre>';die();*/

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $taxConfig = array(
            'includeTax'        => Mage::helper('tax')->priceIncludesTax(),
            'showIncludeTax'    => Mage::helper('tax')->displayPriceIncludingTax(),
            'showBothPrices'    => Mage::helper('tax')->displayBothPrices(),
            'defaultTax'        => $defaultTax,
            'currentTax'        => $currentTax,
            'inclTaxTitle'      => Mage::helper('catalog')->__('Incl. Tax'),
        );

        $config = array(
            'attributes'        => $attributes,
            'attributesoptions'        => $attributesoptions,
            'template'          => str_replace('%s', '#{price}', $store->getCurrentCurrency()->getOutputFormat()),
//            'prices'          => $this->_prices,
            'basePrice'         => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getFinalPrice())),
            'oldPrice'          => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getPrice())),
            'productId'         => $this->getProduct()->getId(),
            'chooseText'        => Mage::helper('catalog')->__('Choose an Option...'),
            'taxConfig'         => $taxConfig,
        );
        
        return Mage::helper('core')->jsonEncode($config);
    }
}
