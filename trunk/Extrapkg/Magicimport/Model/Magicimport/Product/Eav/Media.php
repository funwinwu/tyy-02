<?php
define('IMAGE_TARGET',Mage::getBaseDir('media').DS.'catalog'.DS.'product'.DS); //windows

class Extrapkg_Magicimport_Model_Magicimport_Product_Eav_Media extends Extrapkg_Magicimport_Model_Magicimport_Product_Eav
{
	private $data;
	protected $attrCodes = array ('thumbnail','small_image','image'  );
	public function update( $product_id,$medias,$store_id=0 )
	{
		$medias = (array)$medias;
		if( count( $medias ) ){
			foreach( $medias as $_media ){
				if( is_file( $_media ) && file_exists( $_media ) ){
					$file_original = md5( $_media );					
					//$file = substr ( $resRow ['image' . $i], 1 );
					$folder = IMAGE_TARGET . substr ( $file_original, 0, 1 ) . DS . substr ( $file_original, 1, 1 );
					//image_path use web path,don't use the physical path
					$this->data[] = '/' . substr ( $file_original, 0, 1 ) . '/' . substr ( $file_original, 1, 1 ) . '/'	. $file_original;
					
					
					if (! is_dir ( $folder )) {
						spip_make_dir ( $folder,0775 );
					}
					if( !file_exists( $folder . DS . $file_original ) ){
						copy ( $_media, $folder . DS . $file_original );
						chmod ( $folder . DS . $file_original, 0775 );
						chgrp ( $folder . DS . $file_original, 'apache' );
					}else{
						$mtime = filemtime( $folder . DS . $file_original );
						if( time() - $mtime > 30*24*3600 ){
							copy ( $_media, $folder . DS . $file_original );
							chmod ( $folder . DS . $file_original, 0775 );
							chgrp ( $folder . DS . $file_original, 'apache' );
						}
					}
				}
			}
			
			//echo 'Now update product images:'.$product_id.'<br />';
			$this->setCatalogProductEntityVarchar ( $product_id );
		}
	}
	
	function setCatalogProductEntityVarchar( $entity_id) {
		$attribute_media_gallery = $this->_getEavAttribute ( 'media_gallery' );
		$entity_type_id = $this->_getEntityType();
		//print_r( $this->data );
		//echo '<br />';
		for($i = 0; $i < 3; $i++) { //import image1,image2,image3
			$image = '';
			if (isset( $this->data[$i] ) ) { //only image1 setted as small_image,thumb_image,image
				$image = $this->data[$i];
			}else{
				if (isset( $this->data[$i-1] ) ) { //only image1 setted as small_image,thumb_image,image
					$image = $this->data[$i-1];
				}else{
					if (isset( $this->data[$i-2] ) ) { //only image1 setted as small_image,thumb_image,image
						$image = $this->data[$i-2];
					}
				}
			}
			
			$attrCode = $this->attrCodes[$i];
			//image small_image	thumbnail
			$attribute_id = $this->_getEavAttribute ( $attrCode );
			
			if ($image) {
				$value_id_xxx = $this->db->fetchOne ( "select value_id from catalog_product_entity_varchar where `entity_type_id` = '{$entity_type_id}' and `attribute_id` = '{$attribute_id}' and `entity_id` = '{$entity_id}'" );
				if ($value_id_xxx) {
					$this->db->query ( "UPDATE `catalog_product_entity_varchar` SET `value` = '{$image}' WHERE `entity_id` = '{$entity_id}' and `attribute_id` = '{$attribute_id}' and `entity_type_id` = '{$entity_type_id}'" );
				} else {						
					$this->db->query ( "insert into catalog_product_entity_varchar (entity_type_id,attribute_id,store_id,entity_id,value) values ('{$entity_type_id}','{$attribute_id}','0','{$entity_id}','{$image}')" );
				}
			}	
				
			if (isset( $this->data[$i] ) ) { 
				$value_id = $this->db->fetchOne ( "select value_id from catalog_product_entity_media_gallery where `value` = '{$this->data[$i]}' and `entity_id` = '{$entity_id}' and `attribute_id` = '{$attribute_media_gallery}'" );
				if ($value_id)
					$this->db->query ( "UPDATE `catalog_product_entity_media_gallery_value` SET `label` = '', `position` = '{$i}' WHERE `value_id` = '{$value_id}'" );
				else {
					$this->db->query ( "insert into catalog_product_entity_media_gallery (attribute_id,entity_id,value) values ('{$attribute_media_gallery}','{$entity_id}','{$this->data[$i]}')" );
					$lastInsertId = $this->db->lastInsertId ();
					$this->db->query ( "insert into catalog_product_entity_media_gallery_value (value_id,store_id,label,position,disabled) values ('{$lastInsertId}','0','','{$i}','0')" );
				}
			}
					
		}		
	}
	
	protected function _getEntityType( $entityTypeCode = 'catalog_product') {
		return Mage::getModel ( 'eav/entity_type' )->loadByCode ( $entityTypeCode )->getEntityTypeId ();
	}
	
	protected function _getEavAttribute( $attributeCode,  $entityTypeCode = 'catalog_product') {
		$entityTypeId = $this->_getEntityType ( $entityTypeCode );
		return $this->db->fetchOne ( 'SELECT `attribute_id` FROM eav_attribute where `entity_type_id` = "' . $entityTypeId . '" and `attribute_code` = "' . $attributeCode . '"' );
	}
	
	protected function _getEavAttributeSet( $entityTypeCode = 'catalog_product',  $attributeSetName = 'Default') {
		$entityTypeId = $this->_getEntityType ( $entityTypeCode );
		return $this->db->fetchOne ( 'SELECT `attribute_set_id` FROM eav_attribute_set where `entity_type_id` = "' . $entityTypeId . '" and `attribute_set_name` = "' . $attributeSetName . '"' );
	}
}

function spip_make_dir( $dir,$mode = '0775' )
{
	$dirs = explode(DS, $dir);
	
	$str_dir = "";
	for($i=0; $i<count($dirs); $i++)
	{		
		if( $i == 0 )
			$str_dir .= $dirs[$i];
		else $str_dir .= DS."{$dirs[$i]}";
		if(!is_dir ($str_dir)){
			if( !empty( $str_dir ) ){
				mkdir ($str_dir, $mode);
				chmod( $str_dir, $mode );
			}
		}
	}
	
	return ;
}