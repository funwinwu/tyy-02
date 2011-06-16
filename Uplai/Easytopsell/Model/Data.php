<?php
Class Uplai_Easytopsell_Model_Data {


    public function getSellDate($days) {
        $product = Mage::getModel('catalog/product');
        $product=array();
        $product['todaydate'] = date('Y-m-d H:i:s', time());
        $product['startdate'] = date('Y-m-d H:i:s', time() - 60 * 60 * 24 * $days);
        return $product;

    }
    public function isExtensionEnabled() {
       return Mage::getStoreConfig('easytopsell/options/enable');
    }
    public function isOutOfStock() {
       return Mage::getStoreConfig('easytopsell/options/outofstock');
    }
    public function getTitle()
    {
       return Mage::getStoreConfig('easytopsell/options/blocktitle');
    }
    public function getNoProductsText()
    {
       return Mage::getStoreConfig('easytopsell/options/noproducttext');
    }
    public function getHomepageCatID()
    {
        return Mage::getStoreConfig('easytopsell/homepageoptions/homecat');
    }


    
    public function getCategory ($id){
            $categoryId = $id;
            if (!$categoryId || !is_numeric($categoryId))
                    $category = Mage::registry("current_category");
            else {
                    $category = Mage::getModel("catalog/category")->load($categoryId);
                    if (!$category->getId())
                            $category = Mage::registry("current_category");
            }
            return $category;
    }


    public function getHomepageProductsLimit() {
        $count = (int) Mage::getStoreConfig('easytopsell/homepageoptions/homecount');
        if ($count <=0) $count=5;
        return $count;
    }
    public function getHomepageDaysLimit() {
        $count = (int) Mage::getStoreConfig('easytopsell/homepageoptions/homedays');
        if ($count <=0) $count=5;
        return $count;
    }

    
    public function getCatProductsLimit() {
        $count = (int) Mage::getStoreConfig('easytopsell/catpageoptions/catcount');
        if ($count <=0) $count=5;
        return $count;
    }
    public function getCatDaysLimit() {
        $count = (int) Mage::getStoreConfig('easytopsell/catpageoptions/catdays');
        if ($count <=0) $count=5;
        return $count;
    }






}