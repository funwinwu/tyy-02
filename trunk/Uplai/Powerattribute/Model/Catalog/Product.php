<?php
/**
 * Catalog product model
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Uplai_Powerattribute_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
   /*function add by Ken. 20100121*/
    public function getData($key='', $index=null)
    {       
    	
    	if ('sku'==$key || empty( $key ) ) {
			if( isset( $this->_data['sku'] ))
				$this->_data['sku'] = trim( $this->_data['sku'] );
		}
		
		//load data from default product(multiple product with same sku)	
		if( Mage::app()->getRequest()->getControllerModule() != 'Mage_Adminhtml' && $this->_data['type_id'] == 'configurable' ){//only in frentend
			if ('image'==$key  || 'thumbnail'==$key || 'small_image'==$key || 'base_image'==$key ){	
				if( !isset( $this->_data[$key] ) or ( !strcasecmp(trim($this->_data[$key]),'no_selection') or !file_exists( Mage::getBaseDir('media') . DS.'catalog'.DS.'product'.$this->_data[$key] ) ) ){
					$default_product = $this->loadDefaultProduct( $this );
					
					if( is_object( $default_product ) && $default_product->getId() != $this->getId()  ){						
						$this->_data[$key] = $default_product->getData( $key );						
					}
				}
			}
			
			if( 'media_gallery' == $key ){
				$default_product = $this->loadDefaultProduct( $this );					
				if( is_object( $default_product ) && $default_product->getId() != $this->getId()  ){
					$gallery = $this->_data[$key]['images'];
					$this->_data[$key] = $default_product->getData( $key );
					if( !empty( $gallery ) ){
						$this->_data[$key]['images'] = $this->mergeGallery( $this->_data[$key]['images'],$gallery );
					}
				}
			}
		}
    	
        return parent::getData($key, $index);
    }
	
	private function mergeGallery( $gallery,$gallery2 )
    {
    	$key_array = array();
    	foreach( $gallery as $_gallery ){
    		$key_array[] = $_gallery['file'];
    	}
    	
    	foreach( $gallery2 as $_gallery2 ){
    		if( !in_array( $_gallery2['file'],$key_array ) && file_exists( Mage::getBaseDir('media') . DS.'catalog'.DS.'product'.$_gallery2['file'] ) ){
    			$gallery[] = $_gallery2;
    		}
    	}
    	
    	return $gallery;
    }
    
    private function loadDefaultProduct( $product )
    {
    	static $default_product;
    	
    	if( empty( $default_product ) ){
	    	$allProducts = $product->getTypeInstance(true)
	                ->getUsedProducts(null, $product);
	        foreach ($allProducts as $product) {
	            if ($product->isSaleable()) {
	                $products[] = $product;
	            }
	        }
	        
	        $pre_count = 0;    	
	    	foreach ($products as $_product) {
	    		$_product->load();
	            if( $_product->getMediaGalleryImages()->getSize() > $pre_count){
		            $default_product = $_product;
		            $pre_count = $_product->getMediaGalleryImages()->getSize();
	            }
	        }
    	}    	
    	return $default_product;
    }
}
