<?php

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Stock extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{		
	const STOCK_ITEM_TABLE = 'cataloginventory_stock_item';
	const STOCK_STATUS_TABLE = 'cataloginventory_stock_status';
	private $website_id;
	private $stock_id = 1;
	
	public function setWebsiteId($website_id)
	{
		$this->website_id = $website_id;
		return $this;
	}
	
	public function setStockId($stock_id)
	{
		$this->stock_id = $stock_id;
		return $this;
	}
	
	public function update( $product_id,$qty,$store_id=0 )
	{
		$this->updateStock( $product_id,$qty );
		$this->updateStockStatus( $product_id,1 );
	}
	
	protected function updateStock( $product_id,$qty )
	{
		$item_id = $this->getStockId( $product_id );
		$this->db->query( "update ".self::STOCK_ITEM_TABLE." set qty='{$qty}' where item_id='{$item_id}'");
		return;
	}	
	
	protected function getStockId( $product_id )
	{
		$query = "select item_id from ".self::STOCK_ITEM_TABLE." where product_id={$product_id} and stock_id={$this->stock_id}";
		$value_id = $this->db->fetchOne ( $query );
		if( !$value_id ){
			$value_id = $this->newStockItem( $product_id );
		}
		return $value_id;
	}
	
	/*create new stock item*/
	private function newStockItem( $product_id )
	{		
		$this->db->query( "insert into ".self::STOCK_ITEM_TABLE." (product_id,stock_id,is_in_stock) values ('{$product_id}',{$this->stock_id},1 )");
		return $this->getStockId( $product_id );
	}
	
	private function getQty( $product_id )
	{
		$query = "select qty from ".self::STOCK_ITEM_TABLE." where product_id={$product_id} and stock_id={$this->stock_id}";
		$qty = $this->db->fetchOne ( $query );
		return $qty;
	}
	
	
	/*create new stock status*/
	public function updateStockStatus( $product_id,$is_in_stock )
	{
		$qty = $this->getQty( $product_id );
		$this->db->query( "delete from ".self::STOCK_STATUS_TABLE." where product_id = '{$product_id}' and website_id ='{$this->website_id}' and stock_id = '{$this->stock_id}'");
		$this->db->query( "insert into ".self::STOCK_STATUS_TABLE." (product_id,website_id,stock_id,qty,stock_status) values ('{$product_id}','{$this->website_id}','{$this->stock_id}','{$qty}','{$is_in_stock}' )");
		$this->db->query( "update ".self::STOCK_ITEM_TABLE." set is_in_stock='{$is_in_stock}' where product_id='{$product_id}' limit 1");//,'{$this->website_id}','{$this->stock_id}','{$qty}','{$is_in_stock}' )");
		return;
	}
}