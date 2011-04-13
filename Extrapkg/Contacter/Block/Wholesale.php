<?php
class Extrapkg_Contacter_Block_Wholesale extends Mage_Core_Block_Template
{
	public $tab_id;
	public $static_block;
	public $no_selected = true;
	public $image_display;
	
	public function _prepareLayout()
    {
		if ( stristr( $_SERVER['REQUEST_URI'],'contacter' ) && $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label'=>Mage::helper('catalogsearch')->__('Home'),
                'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ))->addCrumb('Wholesale', array(
                'label'=>Mage::helper('catalogsearch')->__('Wholesale')
            ));
        }
    	return parent::_prepareLayout();
    }
    
     public function getContacter()     
     { 
        if (!$this->hasData('contacter')) {
            $this->setData('contacter', Mage::registry('contacter'));
        }
        return $this->getData('contacter');
        
    }
    
    public function setTab( $tab_id )
    {
    	$this->tab_id = $tab_id;
    }
    
    public function getFormAction()
    {
        return $this->getUrl('contacter/index/post');
    }
    
    public function getCountryOptions()
    {
    	$thml = '';
    	$countries = Mage::getModel('adminhtml/system_config_source_country')
       ->toOptionArray();
       unset($countries[0]);
       $countryId = Mage::getStoreConfig('general/country/default');
      
       if( !empty( $countries ) ){
       		foreach( $countries as $country ){
       			$thml .= "<option value=\"{$country['value']}\" ".( isset( $countryId ) ? ( $countryId == $country['value'] ? 'selected="selected"':'' ):'' ).">{$country['label']}</option>";
       		}
       }
       return $thml;
    }
    
    public function getYearsBusinessOptions()
    {
    	$thml = '';
    	
      
   		for( $year=1;$year<10;$year++ ){
   			$thml .= "<option value=\"{$year}\">{$year}</option>";
   		}
   		$thml .= "<option value=\"11\">10 years above</option>";
      
       return $thml;
    }
    
    public function parseStaticBlock( $id )
    {
    	$cms_content = $this->getChildHtml( $id );
    	$cms_content = eregi_replace( "<[^<]*>",'',$cms_content );    	
    	preg_match_all( "/\{\{([^\{]*)\}\}/i",$cms_content,$elements );
    	if( isset( $elements[1] ) ){
    		if( count( $elements[1] ) == 4 ){
    			$_element = $elements[1];
	    		$this->static_block[ $id ]['title_unselected'] = str_replace( '[web_url]/' , Mage::getBaseUrl('web') , $_element[0] );	    		  		
	    		$this->static_block[ $id ]['title_selected'] = str_replace( '[web_url]/' , Mage::getBaseUrl('web') , $_element[1] );	    		  		
	    		$this->static_block[ $id ]['message'] = str_replace( '[web_url]/' , Mage::getBaseUrl('web') , $_element[2] );	    		  		
	    		$this->static_block[ $id ]['big_image'] = str_replace( '[web_url]/' , Mage::getBaseUrl('web') , $_element[3] );	  
	    		stristr( $_SERVER['REQUEST_URI'],$id ) && $this->static_block[ $id ]['current'] = 1 ;	  		
	    		stristr( $_SERVER['REQUEST_URI'],$id ) && $this->no_selected = 0;	 	  		
    		}
    	}
    }
    
    public function cn_substr_utf8($str, $length, $start=0)
	{
		if(strlen($str) < $start+1)
		{
			return '';
		}
		preg_match_all("/./su", $str, $ar);
		$str = '';
		$tstr = '';
	
		
		for($i=0; isset($ar[0][$i]); $i++)
		{
			if(strlen($tstr) < $start)
			{
				$tstr .= $ar[0][$i];
			}
			else
			{
				if(strlen($str) < $length + strlen($ar[0][$i]) )
				{
					$str .= $ar[0][$i];
				}
				else
				{
					break;
				}
			}
		}
		return $str;
	}
	
	public function checkCurrent( $id )
	{
		if( isset( $this->static_block[ $id ] ) && isset( $this->static_block[ $id ]['current'] ) && $this->static_block[ $id ]['current'] ){
			return true;
		}else false;
	}
	
	public function checkStaticBlock()
	{		
		if( $this->no_selected && isset( $this->static_block[ 'our-craftsmanship-inspiration' ] ) ){
    			$this->static_block[ 'our-craftsmanship-inspiration' ]['current'] =  1;
    			$this->static_block[ 'our-craftsmanship-inspiration' ][ 'title' ] = $this->static_block[ 'our-craftsmanship-inspiration' ][ 'title_selected' ];	
				$this->static_block[ 'our-craftsmanship-inspiration' ][ 'big_image' ] && $this->image_display	= $this->static_block[ 'our-craftsmanship-inspiration' ][ 'big_image' ];
				$this->static_block[ 'our-craftsmanship-inspiration' ][ 'message' ] = $this->cn_substr_utf8( $this->static_block[ 'our-craftsmanship-inspiration' ][ 'message' ],300 );
    	}
		
		foreach( $this->static_block as $key => $var ){
			if( isset( $var['current'] ) && $var['current'] ){
				$this->static_block[ $key ][ 'title' ] = $this->static_block[ $key ][ 'title_selected' ];	
				$this->static_block[ $key ][ 'big_image' ] && $this->image_display	= $this->static_block[ $key ][ 'big_image' ];
				$this->static_block[ $key ][ 'message' ] = $this->cn_substr_utf8( $this->static_block[ $key ][ 'message' ],300 );
				
			}else{
				$this->static_block[ $key ][ 'title' ] = $this->static_block[ $key ][ 'title_unselected' ];
				$this->static_block[ $key ][ 'message' ] = ( strlen( $this->static_block[ $key ][ 'message' ] ) > 100 ) ? $this->cn_substr_utf8( $this->static_block[ $key ][ 'message' ],100 ).'...<span class="more">'.$this->__('more').'</span>':'';
			}
			
			$this->static_block[ $key ][ 'link' ] = Mage::getBaseUrl('web').'index.php/our-craftsmanship?'.$key;
		}
		
		
	}
	
	/*get product collection under category*/
    public function getProductList()
    {  
    	$session = Mage::getSingleton('catalog/session');
    	$products_make_offer = $session->getData( "products_make_offer" );
    	$product_collection = array();
    	
    	
    	if( !empty( $products_make_offer ) ){   
	        $product_collection = Mage::getModel( "catalog/product" )->getCollection();
	
	        $product_collection->joinField('qty',
	                'cataloginventory/stock_item',
	                'qty',
	                'product_id=entity_id',
	                '{{table}}.stock_id=1',
	                'left');
	
	       $product_collection->addAttributeToSelect('model')
		   ->addAttributeToSelect('name')
		   ->addAttributeToSelect('image');
		   	$product_collection->addAttributeToFilter("entity_id",array( 'in' => array(  $products_make_offer['product_id'] ) ) );
		  	
    	}	
        return $product_collection;
    }
    
    public function getOfferQty( $product_id )
    {
    	return Mage::getSingleton( "contacter/contacter" )->getOfferQty( $product_id );
    }
    
    public function getOfferPrice( $product_id )
    {
    	return Mage::getSingleton( "contacter/contacter" )->getOfferPrice( $product_id );
    }
	
	/**
     * Retrieve form data
     *
     * @return Varien_Object
     */
    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (is_null($data)) {
            $data = new Varien_Object(Mage::getSingleton('customer/session')->getFormData(true));
            $this->setData('form_data', $data);
        }
        return $data;
    }
}
?>