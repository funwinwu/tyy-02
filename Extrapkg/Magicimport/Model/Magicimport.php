<?php

class Extrapkg_Magicimport_Model_Magicimport extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('magicimport/magicimport');
    }
    
	
	public function saveData( $product_set )
	{		
		//clean
		$data_model = Mage::getModel("magicimport/magicimport_magicdata");
		$data = array();
		$data['magicimport_id'] = $this->getId();
		//insert store id into data content.		
		!isset( $product_set['store_id'] ) && $this->getData('store_id') && $product_set['store_id'] = $this->getData('store_id'); //and $this->getData('store_id') is not 0
		
		$data['content'] = json_encode( $product_set );
		$data['status'] = 'p';
		$data['hash'] = md5( $this->getId().json_encode( $product_set ) );
		$data['created_time'] = date( "Y-m-d H:i:s",$_SERVER['REQUEST_TIME'] );
		if( isset( $product_set['entity_id'] ) && !empty( $product_set['entity_id'] ) || isset( $product_set['sku'] ) && !empty( $product_set['sku'] ) ){
			$data_model->setData( $data );
			$data_model->save();
		}
	}
	
	public function refreshDataTotal()
	{		
		$data_model = Mage::getModel("magicimport/magicimport_magicdata")->getCollection()
								->addFieldToFilter("magicimport_id",$this->getId());
		
		$this->setData('data_rows',$data_model->getSize());
		return $this->save();
	}
	
	public function refreshStat()
	{
		$this->refreshSuccessTotal();
		$this->refreshFailedTotal();
		if( $this->getData('data_rows' ) == $this->getData('success' ) + $this->getData('failed' ) ){
			$this->setData('main_status','f' );
		}
		$this->save();
	}
	
	public function refreshSuccessTotal()
	{
		$data_model = Mage::getModel("magicimport/magicimport_magicdata")->getCollection()
								->addFieldToFilter("magicimport_id",$this->getId())
								->addFieldToFilter("status",Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH);
		$this->setData('success',$data_model->getSize());
		return $this->save();
	}
	
	public function refreshFailedTotal()
	{
		$data_model = Mage::getModel("magicimport/magicimport_magicdata")->getCollection()
								->addFieldToFilter("magicimport_id",$this->getId())
								->addFieldToFilter("status",Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED);
		$this->setData('failed',$data_model->getSize());
		return $this->save();
	}
	
	public function exist( $product_set )
	{
		$key = md5( $this->getId().json_encode( $product_set ) );		
		$magic_data = Mage::getModel("magicimport/magicimport_magicdata")->load( $key,'hash' );		
		return $magic_data->getId();
	}
    public function import( $stepping )
    {
    	if( $this->getData('import_type') == Extrapkg_Magicimport_Model_Type::TYPE_ADD ){
    		$this->add( $stepping );
    	}else if( $this->getData('import_type') == Extrapkg_Magicimport_Model_Type::TYPE_UPDATE ){
    		$this->update( $stepping );
    	}else if( $this->getData('import_type') == Extrapkg_Magicimport_Model_Type::TYPE_UPDATE_PRICISE ){
    		$this->updatePricise( $stepping );
    	}else if( $this->getData('import_type') == Extrapkg_Magicimport_Model_Type::TYPE_UPDATE_SKU ){
    		$this->update( $stepping );
    	}
    	return;
    }
	
	/*
	public function updatePricise( $stepping )
	{
		$magic_data_set = $this->getSteppingData( $stepping );
		foreach( $magic_data_set as $_magic_data ){
			$_magic_data->updatePricise();			
			$_magic_data->save();
		}
	}
	*/
    
    public function add( $stepping )
    {
		$magic_data_set = $this->getSteppingData( $stepping );
		
		foreach( $magic_data_set as $_magic_data ){
			$_magic_data->add();
			$_magic_data->save();
		}
    	return;
    }
    
    public function update( $stepping )
    {
    	$magic_data_set = $this->getSteppingData( $stepping );
		foreach( $magic_data_set as $_magic_data ){
			$_magic_data->update();
			$_magic_data->save();
    	}
    	return;
    }
    
	private function getSteppingData( $stepping )
	{
		try{
		
		return Mage::getModel("magicimport/magicimport_magicdata")
				->factory( $this->getData('data_type') )
				->getCollection()
				->addFieldToFilter( "magicimport_id",$this->getId() )
				->addFieldToFilter( "status",Extrapkg_Magicimport_Model_Datastatus::TYPE_PENDDING )
				->setPageSize( $stepping );
		}catch (Exception $e){
			echo $e;
		}
	}
    
    protected function _removeEmptyField( $data )
    {
    	$new_data = array();
    	foreach( $data as $key => $var ){
    		if( !empty( $key ) && !empty( $var ) ){
    			$new_data[ $key ] = $var;
    		}
    	}
    	return $new_data;   	
    }
	
	public function restore()
	{
		$finish_data = Mage::getModel("magicimport/magicimport_magicdata")->factory( $this->getData('data_type') )->getCollection()
													->addFieldToFilter( "magicimport_id",$this->getId() )
													->addFieldToFilter( "status",Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
		if( $finish_data->getSize() ){
			foreach( $finish_data as $_data ){
				$_data->restore();
			}
			return true;
		}else return false;
	}
	
	protected function _beforeDelete()
	{
		$finish_data = Mage::getModel("magicimport/magicimport_magicdata")->getCollection()
													->addFieldToFilter( "magicimport_id",$this->getId() );
		if( $finish_data->getSize() ){
			foreach( $finish_data as $_data ){
				$_data->delete();
			}
		}
		return;
	}
     
}