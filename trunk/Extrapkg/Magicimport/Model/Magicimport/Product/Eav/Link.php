<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Link extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{		
	const LINK_TABLE = 'catalog_product_link';	
	
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
	
	public function update( $product_id,$sku,$store_id=0 )
	{
		if( $this->product_id >0 && $this->link_type > 0  ){
			$this->cleanLink();
			$skus = explode( ',',$sku );
			foreach( $skus as $_sku ){
				$this->assignLink( $this->getLinkProductIds($_sku) );
			}
		}
		return;
	}
	
	private function cleanLink()
	{
		$this->db->query( "delete from ".self::LINK_TABLE." where product_id = '{$this->product_id}' and link_type_id ='{$this->link_type}'");
		
	}
	
	private function getLinkProductIds( $sku )
	{
		$product_ids = array();
		$query = "select entity_id as product_id from catalog_product_entity where {$this->sku_field} like '{$sku}%'";
		$result = $this->db->fetchAll( $query );
		foreach( $result as $_row ){
			$product_ids[] = $_row['product_id'];
		}
		return $product_ids;
	}
	
	private function assignLink($product_ids)
	{		
		foreach( $product_ids as $_product_id ){
			$this->db->query( "insert into ".self::LINK_TABLE."(product_id,linked_product_id,link_type_id) values('{$this->product_id}','{$_product_id}','{$this->link_type}')");
			//$this->db->query( "insert into ".self::LINK_TABLE."(product_id,linked_product_id,link_type_id) values('{$this->product_id}','{$_product_id}','{$this->link_type}')");
			$this->addReflectLink( $_product_id,$this->product_id );
		}
	}
	
	private function addReflectLink($product_id,$linked_product_id)
	{
		if( !$this->db->fetchOne("select link_id from ".self::LINK_TABLE." where product_id='{$product_id}' and linked_product_id='{$linked_product_id}'") ){
			$this->db->query( "insert into ".self::LINK_TABLE."(product_id,linked_product_id,link_type_id) values('{$product_id}','{$linked_product_id}','{$this->link_type}')");
		}
	}
	
	
}