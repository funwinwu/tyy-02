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
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * New products block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Product_Sunvalleylist extends Mage_Catalog_Block_Product_List
{
    protected $_productsCount = null;

    const DEFAULT_PRODUCTS_COUNT = 6;

    public function setProductsCount($count)
    {
        $this->_productsCount = $count;
        return $this;
    }

    public function getProductsCount()
    {
        if (null === $this->_productsCount) {
            $this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
        }
        return $this->_productsCount;
    }

	/*get top seller products*/
	public function setTopSellersCollection()
	{
		$collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()        
            ->addAttributeToSort('ordered_qty', 'desc')
            ->setPageSize($this->getProductsCount())
			->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left')
            ->setCurPage(1);
        $this->setProductCollection($collection);
	}
	
	/*get top special  products*/
	public function setSpecialCollection()
	{
		 $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            //->addAttributeToFilter( 'daily_special',1 )
            ->setPageSize($this->getProductsCount())
			->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left')
            ->setCurPage(1)
        ;
		$collection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
                     ->addAttributeToFilter('special_to_date', array('or'=> array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                    ), 'left');
		$collection->getSelect()->order("rand(),1");
        $this->setProductCollection($collection);
	}
		
	public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }
	
	/*function add by Ken.*/
	protected function _prepareLayout()
	{
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			//$breadcrumbs->cleanBreadCrumbs();
			$breadcrumbs->addCrumb('home', array(
                'label'=>Mage::helper('catalogsearch')->__('Home'),
                'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ));
            $type = Mage::app()->getFrontController()->getRequest()->getParam('type');
			$type = trim( $type,'/' );
			if( $type == 'new' ){
	            $breadcrumbs->addCrumb('liste-des-produits', array(
	                'label'=>Mage::helper('catalogsearch')->__('New arrivals')
	            ));	
			}
	        else if( $type == 'promotion' ){
	        	$breadcrumbs->addCrumb('liste-des-produits', array(
	                'label'=>Mage::helper('catalogsearch')->__('Daily specials')
	            ));	
	        }else if( $type == 'topsellers' ){
	        	$breadcrumbs->addCrumb('liste-des-produits', array(
	                'label'=>Mage::helper('catalogsearch')->__('Top Sellers')
	            ));	
	        }
		}
	}
	
	protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {        	
	        $this->_productCollection = Mage::getResourceModel('catalog/product_collection');
	        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
	        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_productCollection);
	        
	        $this->_productCollection = $this->_addProductAttributesAndPrices($this->_productCollection)->addStoreFilter();
	        $this->_productCollection->joinField('qty',
										'cataloginventory/stock_item',
										'qty',
										'product_id=entity_id',
										'{{table}}.stock_id=1',
										'left')
									->addAttributeToSelect("model");
									
	        $type = Mage::app()->getFrontController()->getRequest()->getParam('type');
			$type = trim( $type,'/' );
			$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
	        if( $type == 'new' ){			   
	           $this->_productCollection->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
	            ->addAttributeToFilter('news_to_date', array('or'=> array(
	                0 => array('date' => true, 'from' => $todayDate),
	                1 => array('is' => new Zend_Db_Expr('null')))
	            ), 'left')
	            ->addAttributeToSort('news_from_date', 'desc');	            
			}
	        else if( $type == 'promotion' ){
			/*
	        	$this->_productCollection
                     ->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
                     ->addAttributeToFilter('special_to_date', array('or'=> array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                    ), 'left');
			*/	
				$this->_productCollection->addStoreFilter()
					->addAttributeToFilter( 'daily_special',1 );
				

	        }else if( $type == 'topsellers' ){			
	        	$this->_productCollection->addStoreFilter()
					->addAttributeToFilter( 'top_sell',1 );
					
	        }
			$this->_productCollection->getSelect()->order( '_table_qty.qty desc' );
        }
        return $this->_productCollection;
    }
	
	public function setTopsellCollection()
	{
		$collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter( 'top_sell',1 )
            ->setPageSize($this->getProductsCount())
			->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left')
            ->setCurPage(1)
        ;
		$collection->getSelect()->order("rand(),1");
        $this->setProductCollection($collection);
	}
	
	public function getColumnCount()
	{
		return 5;
	}
}
