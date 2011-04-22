<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Url extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{	
	private $product_id;
	private $separator;
		
	public function setProductId($product_id)
	{		
		$this->product_id = $product_id;
		return $this;
	}
	
	public function update( $product_id,$url_key,$store_id=0 )
	{		
		$this->separator = Mage::getStoreConfig( "catalog/seo/title_separator" );
		if( $this->product_id >0 ){
			$url_key = $this->getUrlKey( $url_key );
			$this->saveUrlKey( $url_key,$store_id );
		}
		return;
	}
	
	private function saveUrlKey( $key,$store_id )
	{
		$arrtibute_id = $this->getAttributeId();
		$query = "select value_id from catalog_product_entity_varchar where entity_id={$this->product_id} and attribute_id={$arrtibute_id} 
					and store_id={$store_id}";
		
		$value_id = $this->db->fetchOne( $query );		
		if( $value_id ){
			$query = "update catalog_product_entity_varchar set value='{$key}' where entity_id={$this->product_id} and attribute_id={$arrtibute_id} 
					and store_id={$store_id}";
		}else{
			$query = "insert into catalog_product_entity_varchar(entity_type_id,attribute_id,store_id,entity_id,value) ";
			$query .= "values( 10,{$arrtibute_id},{$store_id},{$this->product_id},'{$key}' )";
		}		
		$this->db->query( $query );		
		return;
	}
	
	private function getUrlKey( $key )
	{		
		$key = $this->formatUrlKey( $key );		
		$key = $this->uniqueUrlKey( $key );
		return $key;
	}
	private function formatUrlKey( $key )
	{
		$replacor = $this->separator;
		$key = strtolower( $key );
		$key = eregi_replace( '[^a-z0-9]',$replacor,$key );
		$key = eregi_replace( '-+',$replacor,$key );
		return $key;
	}
	
	private function uniqueUrlKey( $key )
	{
		if( $this->isUrlKeyExist( $key ) ){
			$key = $key.$this->separator.'1';
			$key = $this->uniqueUrlKey( $key );
		}
		
		return $key;
	}
	
	private function isUrlKeyExist( $key )
	{
		$attribute_ids = $this->getUrlKeyId();
		$value_id = false;
		foreach( $attribute_ids as $_attribute ){
			if( stristr( $_attribute['backend_model'],'category' ) )
				$query = "select value_id from catalog_category_entity_varchar where attribute_id={$_attribute['attribute_id']} and value='{$key}'";
			else 
				$query = "select value_id from catalog_product_entity_varchar where attribute_id={$_attribute['attribute_id']} and entity_id!={$this->product_id} and  value='{$key}'";
			//echo $query;
			$value_id = $this->db->fetchOne( $query );
		}
		return $value_id;
	}
	
	private function getUrlKeyId()
	{
		$query = "SELECT attribute_id,backend_model FROM `eav_attribute` WHERE `attribute_code` LIKE 'url_key'";
		$result = $this->db->fetchAll( $query );
		return $result;
	}
	
	private function getAttributeId()
	{
		$attribute_ids = $this->getUrlKeyId();
		foreach( $attribute_ids as $_attribute ){
			if( stristr( $_attribute['backend_model'],'product' ) )
				return $_attribute['attribute_id'];
		}
	}
}