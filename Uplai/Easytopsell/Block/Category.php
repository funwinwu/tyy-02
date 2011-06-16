<?php

class Uplai_Easytopsell_Block_Category extends Mage_Catalog_Block_Product_Abstract {


	protected function _construct() {
//		parent::_construct();
//		$this->addData(array(
//			'cache_lifetime' => 86400,
//			'cache_tags' => array('magazentoeasytopsell_category'),
//		));

	}
        protected function _beforeToHtml() {
  
		$storeId = Mage::app()->getStore()->getId();
                $sellDate=$this->getModel()->getSellDate($this->getModel()->getCatDaysLimit());
		$collection = Mage::getResourceModel('reports/product_sold_collection')
                                ->addOrderedQty()
                                ->setStoreId($storeId)
                                ->addStoreFilter($storeId)
				->setDateRange($sellDate['startdate'], $sellDate['todaydate']) //
                                ->addUrlRewrite()
				->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                                ->setOrder('ordered_qty', 'desc')
                                ->setPageSize($this->getModel()->getCatProductsLimit())
                                ->setCurPage(1)
				->setOrder('ordered_qty', 'desc');



                $c = Mage::registry("current_category");
                $catId = $c->getData('entity_id');
                if ($catId>0) {
                    $category = $this->getModel()->getCategory($catId);
                    $collection->addCategoryFilter($category); 
                }
		$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());		
		

		$this->setProductCollection($collection);
		return parent::_beforeToHtml();
	}

    public function getModel() {
        return Mage::getModel('easytopsell/data');
    }
	
	public function getCategory()
	{
		return Mage::registry("current_category");
	}

}














//				->setDateRange($sellDate['startdate'], $sellDate['todaydate'])
//				->addAttributeToFilter('is_salable')
//				->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)))
//				->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
//                                ->addSaleableFilterToCollection()
//                                ->addInStockFilterToCollection()
//                                ->addUrlRewrite()
////                                ->addCategoryFilter($currentCategory)
//                                ->setPageSize($this->getModel()->getHomepageProductsLimit())
//                                ->setCurPage(1)
//				->addOrderedQty()
//				->setOrder('ordered_qty', 'desc');
//

//			->addAttributeToSelect(array('entity_id', 'name', 'price', 'small_image', 'short_description', 'description', 'type_id', 'status'))
//			->addOrderedQty()
//			->setStoreId($storeId)
//			->addStoreFilter($storeId)
////			->addCategoryFilter($currentCategory)
//			->setOrder('ordered_qty', 'desc')
//                        ->setPageSize($this->getModel()->getHomepageProductsLimit())
//                        ->setCurPage(1);
//
//                $collection= array();
//                foreach ($rawcollection as $product) {
////                    $addproduct = $product->getData('is_salable');
//                    $collection[]=$product->getData();
//                }
//                var_dump($rawcollection);
////
//
//                exit();