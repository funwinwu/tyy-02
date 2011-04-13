<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Website extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{		
	const LINK_TABLE = 'catalog_product_website';	
	
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
	
	public function update( $product_id,$website_ids,$store_id=0 )
	{
		if( $this->product_id >0 ){
			$this->cleanLink();
			$this->assignLink( $website_ids );
		}
		return;
	}
	
	private function cleanLink()
	{
		$this->db->query( "delete from ".self::LINK_TABLE." where product_id = '{$this->product_id}'");
		
	}
	
		
	private function assignLink($website_ids)
	{
		foreach( $website_ids as $_website_id ){
			$this->db->query( "insert into ".self::LINK_TABLE."(product_id,website_id) values('{$this->product_id}','{$_website_id}')");
		}
	}
}