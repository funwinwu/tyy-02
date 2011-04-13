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
 * @package    Mage_Poll
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Poll answers model
 *
 * @category   Mage
 * @package    Mage_Poll
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Extrapkg_Magicimport_Model_Magicimport_Product extends Extrapkg_Magicimport_Model_Magicimport_Abstract
{
	const SKUFIELD = 'sku';
	
	protected function _construct()
    {
        parent::_construct();
    	$this->_init('magicimport/magicimport_product');
    }
	
	protected function prepareData()
	{
		$data = parent::prepareData();		
		if( isset( $data['website_ids'] ) )
			$data['website_ids'] = explode( ',',$data['website_ids'] );
		isset( $data['category_ids'] ) && !is_array( $data['category_ids'] ) && $data['category_ids'] = explode( ',',$data['category_ids'] );	
		isset( $data['tier_price'] ) && $data['tier_price'] = str_replace( ';',',',$data['tier_price'] );
		isset( $data['image'] ) && $data['image'] = str_replace( ';',',',$data['image'] );
		isset( $data['images'] ) && $data['images'] = str_replace( ';',',',$data['images'] );
		
		if( isset( $data['sku'] ) && self::SKUFIELD != 'sku' ){
			$data[self::SKUFIELD] = $data['sku'];
			unset( $data['sku'] );
		}
		
		//default data
		!isset( $data['status'] ) && $data['status'] = 1;
		!isset( $data['visibility'] ) && $data['visibility'] = 4;
		return $data;
	}
	public function restore()
	{
		if( $this->getData( 'backup' ) ){
			$backup = json_decode( $this->getData( 'backup' ),true );
			foreach( $backup as $productData ){
				if( !empty( $productData['id'] ) ){	
					$product = Mage::getModel("catalog/product")->load( $productData['id'] );
					//$this->backup( $product,array_keys( $productData ) ); // backup
					isset( $productData['qty'] ) &&
						$productData['stock_data']['qty'] = $productData['qty']; //enable stock management.
					isset( $productData['is_in_stock'] ) &&
						$productData['stock_data']['is_in_stock'] = $productData['is_in_stock']; //enable stock management.	
				   
				   $product->addData($productData);		
					/**
					 * Initialize product categories
					 */
					if( isset( $product_data['category_ids'] ) ){						
						$product->setCategoryIds( $product_data['category_ids'] );
					}					
					$product->save();
					
				}
				$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_RESTORE );
			}			
			$this->save();
			return true;
		}else{		
			return false;   
		}
	}
	
	public function add()
	{		
		$productData = $this->prepareData();
		
		if( !isset( $productData['website_ids'] ) )
			$productData['website_ids'] = array(1);
			
		if( !isset( $productData['qty'] ) )
			$productData['qty'] = 0;
		if( !isset( $productData['url_key'] ) )
			$productData['url_key'] = $productData['name'];
		//get product ids
		$product_id = Mage::getModel("magicimport/magicimport_product_eav")->setSkuField(self::SKUFIELD)->createEntity( $productData[self::SKUFIELD] );
			
		if( $product_id ){
			Mage::getModel("magicimport/magicimport_product_eav")->setSkuField(self::SKUFIELD)
						->update( $product_id,$productData,$this->getMagicImport()->getData('store_id') );
			
			$this->setData( 'affected_entites',$this->getData( 'affected_entites' ) + 1 );
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
			return true;
		}else{
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
			return false;   
		}
	}
	/*
	public function updatePricise()
	{		
		$product_data = $this->prepareData();
		
		if( !empty( $product_data['sku'] ) && stristr( $product_data['sku'],'|' ) ){
			$sku_explode = explode( '|',$product_data['sku'] );
	    	$product_data[self::SKUFIELD] = $sku_explode[0];			
	        unset( $product_data['sku'] );
	        $productData = $product_data;
	        $product2update_collection = Mage::getModel("catalog/product")->getCollection()->addFieldToFilter( self::SKUFIELD,$product_data[self::SKUFIELD] );
	       
			if( isset( $productData['new_sku'] ) && !empty( $productData['new_sku'] ) ){ //update sku
				$productData[self::SKUFIELD] = $productData['new_sku'];					
				unset( $productData['new_sku'] );
			}
			
	        if( $product2update_collection->getSize() ){
				$product_index = 1;
	        	foreach( $product2update_collection as $_product ){
					if( isset( $sku_explode[1] ) && $sku_explode[1] == $product_index++ ){ //only update this one.						
						$product = Mage::getModel("catalog/product")->load( $_product->getId() );
						
						$this->backup( $product->getData(),array_keys( $productData ) ); // backup
						
						isset( $productData['qty'] ) &&
							$productData['stock_data']['qty'] = $productData['qty']; //enable stock management.
						isset( $productData['is_in_stock'] ) &&
							$productData['stock_data']['is_in_stock'] = $productData['is_in_stock']; //enable stock management.					
						
						//tier price
						if( !empty( $productData['tier_price'] ) && is_string( $productData['tier_price'] ) ){
							$all_tiers = explode( ',',$productData['tier_price'] );
							$productData['tier_price'] = array();			
							if( !empty( $all_tiers ) ){
								$index = 0;
								foreach( $all_tiers as $_tier ){
									$qty_price = explode( ':',$_tier );
									if( count( $qty_price ) == 2 ){
										$productData['tier_price'][ $index ]['website_id'] = 0;
										$productData['tier_price'][ $index ]['cust_group'] = 32000;
										$productData['tier_price'][ $index ]['price_qty'] = $qty_price[0];
										$productData['tier_price'][ $index ]['price'] = round( $qty_price[1],2 );
										$index++;
									}
								}
							}
						}
						
					    $product->addData($productData);
				
						
						//Initialize product categories
						if( isset( $product_data['category_ids'] ) ){							
								$product->setCategoryIds( $product_data['category_ids'] );
						}					  
						$product->save();
						$this->setData( 'affected_entites',$this->getData( 'affected_entites' ) + 1 );
						$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
						return true;
					}
	        	}
				$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
				return false;
	        }else{
				$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
				return false;  
			}
        }
	}
	*/
	
	public function update()
	{
		$productData = $this->prepareData();
		if( isset( $productData['entity_id'] ) )
			$this->updateById();
		else if( isset( $productData[self::SKUFIELD] ) )
			$this->updateBySku();
			
		return;
	}
	public function updateById()
	{
		$productData = $this->prepareData();		
		
		if( isset( $productData['new_sku'] ) && !empty( $productData['new_sku'] ) ){ //update sku
			$productData[self::SKUFIELD] = $productData['new_sku'];
			unset( $productData['new_sku'] );
		}			
		
		Mage::getModel("magicimport/magicimport_product_eav")->setSkuField(self::SKUFIELD)->update( $productData['entity_id'],$productData,$this->getMagicImport()->getData('store_id') );
		$this->setData( 'affected_entites',$this->getData( 'affected_entites' ) + 1 );
		
		$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
		return true;
		/*
		}else{
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
			return false;   
		}
		*/
	}
	
	public function updateBySku()
	{
		$productData = $this->prepareData();
		
		//get product ids
		$product_ids = Mage::getModel("magicimport/magicimport_product_eav")->setSkuField(self::SKUFIELD)->getProductIdsBySku( $productData[self::SKUFIELD] );
		
		if( isset( $productData['new_sku'] ) && !empty( $productData['new_sku'] ) ){ //update sku
			$productData[self::SKUFIELD] = $productData['new_sku'];
			unset( $productData['new_sku'] );
		}
			
		if( count($product_ids) ){
			foreach( $product_ids as $_product ){
				Mage::getModel("magicimport/magicimport_product_eav")->setSkuField(self::SKUFIELD)->update( $_product['entity_id'],$productData,$this->getMagicImport()->getData('store_id') );
				$this->setData( 'affected_entites',$this->getData( 'affected_entites' ) + 1 );
			}
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
			return true;
		}else{
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
			return false;   
		}
	}
	
	
	
	protected function _initProduct()
    {
        
		$productId  = 0;
		$store_id = 0;
        $product    = Mage::getModel('catalog/product')
            ->setStoreId( $store_id );

        if (!$productId) {		
			$product->setAttributeSetId( 9 );
            $product->setTypeId( 'simple' );
        }

        // Init attribute label names for store selected in dropdown
        Mage_Catalog_Model_Resource_Eav_Attribute::initLabels($product->getStoreId());      
        $product->setData('_edit_mode', true);

        //Mage::register('product', $product);
       // Mage::register('current_product', $product);
        return $product;
    }
	
	protected function _initProductSave( $product_data )
    {
        $product_data[self::SKUFIELD] = $product_data['sku'];
        unset( $product_data['sku'] );
        !isset( $product_data['qty'] ) && $product_data['qty'] = 1;
		
    	$product    = $this->_initProduct( $product_data );				
        $productData = $product_data;
		
        $productData['tax_class_id'] = 0;
        $productData['status'] = 1;
        $productData['options_container'] = 'container2';
		       
		/*initalize stock data*/
		$productData['stock_data']['manage_stock'] = 1; //enable stock management.
		$productData['stock_data']['use_config_manage_stock'] = 1; //enable stock management.
		$productData['stock_data']['qty'] = $productData['qty']; //enable stock management.
		isset( $productData['is_in_stock'] ) &&
		$productData['stock_data']['is_in_stock'] = $productData['is_in_stock']; //enable stock management.
		!isset( $productData['is_in_stock'] ) &&
		$productData['stock_data']['is_in_stock'] = 1; //enable stock management.
		
		/*tier price*/
		if( !empty( $productData['tier_price'] ) ){
			$all_tiers = explode( ',',$productData['tier_price'] );
			$productData['tier_price'] = array();			
			if( !empty( $all_tiers ) ){
				$index = 0;
				foreach( $all_tiers as $_tier ){
					$qty_price = explode( ':',$_tier );
					if( count( $qty_price ) == 2 ){
						$productData['tier_price'][ $index ]['website_id'] = 0;
						$productData['tier_price'][ $index ]['cust_group'] = 32000;
						$productData['tier_price'][ $index ]['price_qty'] = $qty_price[0];
						$productData['tier_price'][ $index ]['price'] = round( $qty_price[1], 2 );
						$index++;
					}
				}
			}
		}
		
        
        /**
         * Websites
         */
        if (!isset($productData['website_ids'])) {
            $productData['website_ids'] = array( 1 );
        }
		
		$product->addData($productData);
		
        /**
         * Initialize product categories
         */
        if( isset( $product_data['category_ids'] ) ){
	            $product->setCategoryIds( $product_data['category_ids'] );
        }
        /**
         * Initialize product options
         */

        //Mage::dispatchEvent('catalog_product_prepare_save', array('product' => $product, 'request' => $productData));

        return $product;
    }
}