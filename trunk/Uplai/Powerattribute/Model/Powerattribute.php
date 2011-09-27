<?php

class Uplai_Powerattribute_Model_Powerattribute extends Mage_Core_Model_Abstract
{
    private $_db;
	public function _construct()
    {
        parent::_construct();
        $this->_init('powerattribute/powerattribute');
        $this->_db = Mage::getSingleton('core/resource')->getConnection('core_write');
    }
    
    public function getPowerOption( $option_id )
    {
    	$collection =  Mage::getModel("powerattribute/powerattribute")->getCollection()->addFieldToFilter('option_id',$option_id);
    	if( $collection->getSize() ){
    		foreach ( $collection as $_option ){
    			return $_option;
    		}
    	}
    	return '';
    }
    
    public function getPowerOptions( $attribute_id )
    {
    	static $options = array();
    	if( empty( $options ) ){
	    	$collection =  Mage::getModel("powerattribute/powerattribute")->getCollection()->addFieldToFilter('attribute_id',$attribute_id);
	    	if( $collection->getSize() ){
	    		foreach ( $collection as $_option ){
	    			$options['option_'.$_option->getId()] = $_option->getData();
	    		}
	    	}
    	}
    	return $options;
    }
    
    public function saveAttribute( $attribute_id,$data )
    {
    	if( isset( $data['option'] ) ){ 
			//$values = $input['value'];
			//print_r($data['option']['value']);
			//print_r($data['option']['content']);
			//print_r($data['option']['file']);
			foreach ($data['option']['content'] as $option_id => $_color){
				if( !empty( $_color ) ){					
					if( is_numeric($option_id) ){
						$this->_saveAttribute( array( 'attribute_id'=>$attribute_id,'option_id'=>$option_id,'content'=>"#{$_color}",'type'=>'c' ) );
					}else{
						$this->_saveAttribute( array( 'attribute_id'=>$attribute_id,'option_id'=>$this->getOptionIdByName( $attribute_id,$data['option']['value'][$option_id][0] ),'content'=>"#{$_color}",'type'=>'c' ) );
					}
				}
			}
			
			foreach ($data['option']['delete_file'] as $option_id => $_color){
				$this->_removeAttribute( $option_id );
			}
			
			foreach ($_FILES['option']['tmp_name']['file'] as $option_id => $_file){
				if( !empty( $_file ) && empty( $data['option']['content'][$option_id] ) ){	
					$_file = $this->saveImageUpload( $_file,$option_id.'_'.$_FILES['option']['name']['file'][$option_id] );
					
					if( is_numeric($option_id) ){
						$this->_saveAttribute( array( 'attribute_id'=>$attribute_id,'option_id'=>$option_id,'content'=>"{$_file}",'type'=>'f' ) );
					}else{
						$this->_saveAttribute( array( 'attribute_id'=>$attribute_id,'option_id'=>$this->getOptionIdByName( $attribute_id,$data['option']['value'][$option_id][0] ),'content'=>"{$_file}",'type'=>'f' ) );
					}
				}
			}
			exit();
    	}		
    }
    
    private function saveImageUpload( $tmp_file,$file_name )
    {		
    	$path = Mage::getBaseDir('media') . DS . 'powerattribute' ;
    	if( !is_dir($path) )
    		mkdir( $path,'0777' );
    		
		move_uploaded_file( $tmp_file,$path . DS .$file_name );
		return 'powerattribute/' . $file_name;
    }
    
    public function getImageUrl($file_name)
    {
    	$path = Mage::getBaseUrl('media');
    	return $path.'/'.$file_name;
    }
    
    private function _saveAttribute( $data )
    {
    
    	$query = "select * from ".Mage::getSingleton('core/resource')->getTableName('powerattribute')." where attribute_id={$data['attribute_id']} and option_id={$data['option_id']}";
		$option = $this->_db->fetchAll( $query );
		//echo $query."<br>";	
		if( empty( $option ) ){
			$this->_db->query( "insert into ".Mage::getSingleton('core/resource')->getTableName('powerattribute')."(attribute_id,option_id,content,type) values('{$data['attribute_id']}','{$data['option_id']}','{$data['content']}','{$data['type']}')");
			//echo "insert into ".Mage::getSingleton('core/resource')->getTableName('powerattribute')."(attribute_id,option_id,content,type) values('{$data['attribute_id']}','{$data['option_id']}','{$data['content']}','{$data['type']}')"."<br>";	
		}else{
			$this->_db->query( "update ".Mage::getSingleton('core/resource')->getTableName('powerattribute')." set content='{$data['content']}',type='{$data['type']}' where attribute_id={$data['attribute_id']} and option_id={$data['option_id']}" );
			//echo "update ".Mage::getSingleton('core/resource')->getTableName('powerattribute')." set content='{$data['content']}',type='{$data['type']}' where attribute_id={$data['attribute_id']} and option_id={$data['option_id']}"."<br>";	
		}
    }
    
    private function _removeAttribute( $option_id )
    {
    
    	$query = "select * from ".Mage::getSingleton('core/resource')->getTableName('powerattribute')." where  option_id={$option_id}";
		$option = $this->_db->fetchAll( $query );
		//echo $query."<br>";	
		if( empty( $option ) ){
			if( $option[0]['type'] == 'f' ){
				unlink($path = Mage::getBaseDir('media') . DS .str_replace( '/',DS,$option[0]['content'] ));
			}
		}
		
    	$query = "delete from ".Mage::getSingleton('core/resource')->getTableName('powerattribute')." where option_id={$option_id}";
		$this->_db->query( $query );
		return;
    }
    
    public function getOptionIdByName($attribute_id,$name)
    {
    	$optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
        ->setAttributeFilter($attribute_id)        
        ->setPositionOrder('desc', true)
        ->load();
        foreach ( $optionCollection as $_option ){
        	if( $_option->getData('value') == $name )
        		return $_option->getData('option_id');
        }
        return 0;
    }
    
    
}