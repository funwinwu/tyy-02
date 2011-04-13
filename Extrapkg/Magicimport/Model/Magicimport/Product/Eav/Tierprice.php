<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Tierprice extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{		
	const TIERPRICE_TABLE = 'catalog_product_entity_tier_price';
	private $all_groups=1;
	private $customer_group_id = 0;
	private $website_id = 0;
	
	public function setWebsiteId($website_id)
	{
		$this->website_id = $website_id;
		return $this;
	}
	public function setCustomerGroupId($customer_group_id)
	{
		$this->customer_group_id = $customer_group_id;
		if( $this->customer_group_id > 0 )
			$this->all_groups = 0;
		return $this;
	}
	
		
	public function update( $product_id,$tierprices,$store_id=0 )
	{
		//Array ( [0] => Array ( [price_qty] => 5 [price] => 3.5 ) [1] => Array ( [price_qty] => 10 [price] => 3 ) )
		if( count( $tierprices ) ){
			$this->db->query( "delete from ".self::TIERPRICE_TABLE." where entity_id = '{$product_id}' and all_groups ='{$this->all_groups}' and customer_group_id = '{$this->customer_group_id}' and website_id='{$this->website_id}'");
			foreach( $tierprices as $_tierprice ){
				$this->db->query( "insert into ".self::TIERPRICE_TABLE." (entity_id,all_groups,customer_group_id,qty,value,website_id) values ('{$product_id}','{$this->all_groups}','{$this->customer_group_id}','{$_tierprice['price_qty']}','{$_tierprice['price']}','{$this->website_id}' )");
			}
		}
		return;
	}
}