<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav extends Mage_Core_Model_Abstract
{
	static $key;
	static $db;
	static $mdb;
	protected $sku_field = 'model';
	
	public function __construct()
	{
		$this->key = md5( Mage::app()->getRequest()->getPathInfo() );
		$this->db = Mage::getSingleton('core/resource')->getConnection('core_write');	//operate in db directly.
		$this->mdb = Mage::getModel('magicimport/db')->connect();		
	}
	
	public function setSkuField( $field )
	{
		$this->sku_field = $field;
		return $this;
	}
	
	public function createEntity( $sku,$entity_type_id=10,$attribute_set_id=9,$type_id='simple' )
	{
		$create_date = date('Y-m-d H:i:s',time());
		$query = "insert into catalog_product_entity(entity_type_id,attribute_set_id,type_id,{$this->sku_field},created_at) values({$entity_type_id},{$attribute_set_id},'{$type_id}','{$sku}','{$create_date}')";
		
		$this->db->query ( $query );
		return $this->db->fetchOne ( "select entity_id from catalog_product_entity order by entity_id DESC limit 1" );
	}
	
	public function getProductIdsBySku( $sku ){
		return $this->db->fetchAll( "select entity_id from catalog_product_entity where ".$this->sku_field."=\"".$sku."\"" );
	}
	
	public function update( $product_id,$product_data,$store_id=0 )
	{	
		try{			
			if( is_array( $product_data ) && count( $product_data ) ){
				foreach( $product_data as $code=>$value ){
					switch( $code ){
						case 'image':
						case 'images':
							Mage::getModel("magicimport/magicimport_product_eav_media")->update( $product_id,explode( ',',$value ),$store_id );
							break;
						case 'qty': Mage::getModel("magicimport/magicimport_product_eav_stock")->updateStock( $product_id,$value ); break;
						case 'is_in_stock': Mage::getModel("magicimport/magicimport_product_eav_stock")->updateStockStatus( $product_id,$value ); break;
						case 'tier_price': 
							$all_tiers = explode( ',',$value );
							$tier_prices = array();
							if( !empty( $all_tiers ) ){
								foreach( $all_tiers as $key => $_tier ){
									$qty_price = explode( ':',$_tier );
									if( count( $qty_price ) == 2 ){
										$tier_prices[$key]['price_qty'] = $qty_price[0];
										$tier_prices[$key]['price'] = round( $qty_price[1], 2 );
									}
								}
							}
							Mage::getModel("magicimport/magicimport_product_eav_tierprice")->setCustomerGroupId(0)->update( $product_id,$tier_prices ); break;
						case 'related': Mage::getModel("magicimport/magicimport_product_eav_link")->setProductId($product_id)->setLinkType(1)->update( $product_id,$value ); break;
						case 'upsell': Mage::getModel("magicimport/magicimport_product_eav_link")->setProductId($product_id)->setLinkType(4)->update( $product_id,$value ); break;
						case 'crosssell': Mage::getModel("magicimport/magicimport_product_eav_link")->setProductId($product_id)->setLinkType(5)->update( $product_id,$value ); break;
						case 'website_ids': Mage::getModel("magicimport/magicimport_product_eav_website")->setProductId($product_id)->update($product_id,$value); break;
						case 'category_ids': Mage::getModel("magicimport/magicimport_product_eav_category")->setProductId($product_id)->update($product_id,$value); break;
						case 'url_key': Mage::getModel("magicimport/magicimport_product_eav_url")->setProductId($product_id)->update($product_id,$value); break;
						default://normal attribute
							$value = addslashes( $value );
							$attribute_id = $this->getAttributeIdByCode( $code );
							$type = $this->getAttributeTypeByCode( $code );
							if( $attribute_id && $type != 'static' ){
								$this->updateAttribute( $attribute_id,$value,$product_id,10,$store_id,"catalog_product_entity_{$type}" );
							}else{
								$this->updateMainAttribute( $code,$value,$product_id,10);
							}
							break;
					}				
				}	
				//update index.
				//$this->reindexProductAll( $product_id );
				
			}
		}catch(Exception $e){
			echo $e;
			exit();
		}
	}
	
	public function reindexProductAll( $product_id )
	{
		/*
		//product flat.
		$result = Mage::getModel("catalog/product_flat_indexer")->updateProduct( $product_id );		
		*/
		//url
		Mage::getModel("catalog/url")->refreshProductRewrite($product_id);
		
		//stock status
		Mage::getSingleton('cataloginventory/stock_status')
            ->updateStatus($productId);
		Mage::getModel("catalog/product")->load( $product_id )->afterCommitCallback();
		
	}
	
	public function getAttributeIdByCode( $code )
	{
		return $this->db->fetchOne("select attribute_id from eav_attribute where attribute_code=\"{$code}\" and entity_type_id=10");
	}
	
	public function getAttributeTypeByCode( $code )
	{
		return $this->db->fetchOne("select backend_type from eav_attribute where attribute_code=\"{$code}\" and entity_type_id=10");
	}
	
	private function updateAttribute( $attribute_id,$value,$entity_id,$entity_type_id,$store_id,$table_name )
	{		
		try{
			if( is_numeric($attribute_id) && is_numeric($entity_type_id) && is_numeric($entity_id) && is_numeric($store_id) && is_string($table_name) ){
				$value_id = $this->db->fetchOne("select value_id from {$table_name} where attribute_id={$attribute_id} and entity_type_id={$entity_type_id} and entity_id ={$entity_id} and store_id={$store_id}");
				if( !empty( $value_id ) ){
					//echo "update {$table_name} set value=\"{$value}\" where value_id={$value_id}<br />";
					$this->mdb->query("update `{$table_name}` set value=\"{$value}\" where value_id={$value_id}");					
					//Mage::log("update {$table_name} set value=\"{$value}\" where value_id={$value_id}<br />\n",Zend_Log::INFO,"erp_synchronization.log");
				}else{
					//echo "insert into {$table_name}(`entity_type_id`,`attribute_id`,`store_id`,`entity_id`,`value`) values(\"{$entity_type_id}\",\"{$attribute_id}\",\"{$store_id}\",\"{$entity_id}\",\"{$value}\")<br />";
					$this->mdb->query("insert into {$table_name}(`entity_type_id`,`attribute_id`,`store_id`,`entity_id`,`value`) values(\"{$entity_type_id}\",\"{$attribute_id}\",\"{$store_id}\",\"{$entity_id}\",\"{$value}\")");
					//Mage::log("insert into {$table_name}(`entity_type_id`,`attribute_id`,`store_id`,`entity_id`,`value`) values(\"{$entity_type_id}\",\"{$attribute_id}\",\"{$store_id}\",\"{$entity_id}\",\"{$value}\")<br />\n",Zend_Log::INFO,"erp_synchronization.log");
				}
			}
		}catch(Exception $e){
			echo $e;
			exit();
		}
		return;
	}
	
	private function updateMainAttribute( $attribute_code,$value,$entity_id,$entity_type_id )
	{
		
		$mainAttributeCodes = $this->analyzeTable('catalog_product_entity');
		if( isset( $mainAttributeCodes[$attribute_code] ) ){
			$this->db->query("update catalog_product_entity set {$attribute_code}='{$value}' where entity_type_id={$entity_type_id} and entity_id ={$entity_id}");
			//echo "update catalog_product_entity set {$attribute_code}='{$value}' where entity_type_id={$entity_type_id} and entity_id ={$entity_id}";
		}
		return;
	}
	
	private function analyzeTable( $table_name )
	{
		static $cache;
		if( !isset( $cache[$table_name] ) ){
			$fields = $this->db->fetchAll("DESCRIBE `{$table_name}` ");
			$mainAttributeCodes = array();
			
			foreach( $fields as $field ){
				$mainAttributeCodes[$field['Field']] = $field['Type'];
			}
			$cache[$table_name] = $mainAttributeCodes;
		}
		return $cache[$table_name];
	}
	
	public function getAllProductSize()
	{
		
		$all_products = Mage::getModel("catalog/product")->getCollection()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
		return $all_products->getSize();
	}
	
	public function getProductCollection($limit,$offset)
	{
		$selected_products = Mage::getModel("catalog/product")->getCollection()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
		$selected_products->getSelect()->Limit($limit,$offset);	
		
		return $selected_products;
	}
}