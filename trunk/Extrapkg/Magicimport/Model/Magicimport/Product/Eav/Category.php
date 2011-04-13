<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Category extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{		
	const LINK_TABLE = 'catalog_category_product';	
	
	private $link_type = 1;
	private $product_id;
	
	public function setLinkType($link_type)
	{
		$this->link_type = $link_type;
		return $this;
	}
	public function setProductId($product_id)
	{
		$this->product_id = $product_id;
		return $this;
	}
	
	public function update( $product_id,$category_ids,$store_id=0 )
	{
		if( $this->product_id >0 ){
			$this->cleanLink();
			$this->assignLink( $category_ids );
		}
		return;
	}
	
	private function cleanLink()
	{
		$this->db->query( "delete from ".self::LINK_TABLE." where product_id = '{$this->product_id}'");
		$this->db->query( "delete from catalog_category_product_index where product_id = '{$this->product_id}'");
	}
	
		
	private function assignLink($category_ids)
	{
		//$_categories = implode( ',',$category_ids );
		//$this->db->query( "update catalog_product_entity set category_ids='{$_categories}' where entity_id='{$this->product_id}' limit 1");//only for magento 1.3
		
		foreach( $category_ids as $_category_id ){
			$query = "select * from ".self::LINK_TABLE." where product_id={$this->product_id} and category_id={$_category_id}";
			$cateory = $this->db->fetchAll( $query );
			
			if( empty( $cateory ) ){
				$this->db->query( "insert into ".self::LINK_TABLE."(product_id,category_id,position) values('{$this->product_id}','{$_category_id}',1)");
				$this->refreshIndex( $this->product_id,$_category_id );
			}
		}
	}
	
	private function refreshIndex( $product_id,$category_id )
	{
		$query = "select * from catalog_category_entity where entity_id={$category_id}";
		$cateory = $this->db->fetchAll( $query );
		$category = $cateory[0];
		$paths = explode( '/',$category['path'] );
		$position = 0;		
		foreach( $paths as $_category_id ){
			if( $_category_id != 1 && $_category_id != 2 ){
				if( $category_id == $_category_id )
					$is_parent = 1;
				else $is_parent = 0;
				
				$query = "select * from catalog_category_product_index where product_id={$this->product_id} and category_id={$_category_id}";
				$cateory = $this->db->fetchAll( $query );
				if( empty( $cateory ) )
					$this->db->query( "insert into catalog_category_product_index(product_id,category_id,is_parent,position,store_id,visibility) 
									values('{$this->product_id}','{$_category_id}','{$position}','{$is_parent}',1,4)");
				$position++;					
			}
		}
		return;
	}
}