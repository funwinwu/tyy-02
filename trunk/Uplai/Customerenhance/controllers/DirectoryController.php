<?php
class Uplai_Customerenhance_DirectoryController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {    
		echo 'get heer';
		exit();
    	$this->loadLayout();     
		$this->renderLayout();
    }
    
    public function getCityJsonAction()
    {
    	$region_id = Mage::app()->getRequest()->getParam('region_id');
    	$cities = Mage::getModel("customerenhance/city")->getCitysByRegion($region_id);
    	
    	$json = array();
    	if( $cities ){    		
    		foreach ( $cities as $_city ){
    			$json[] = array( 'value'=>$_city['city_id'],'name'=>$_city['name'] );
    		}
    	}
    	if ($json) {
            $this->getResponse()->setHeader('Content-type', 'application/x-json');
            $this->getResponse()->setBody(json_encode( $json ));
        } else {
            $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
        }
    }
    
    public function getDistrictJsonAction()
    {
    	$region_id = Mage::app()->getRequest()->getParam('city_id');
    	$districts = Mage::getModel("customerenhance/city")->getDistrictsByRegion($region_id);
    	
    	$json = array();
    	if( $districts ){    		
    		foreach ( $districts as $_district ){
    			$json[] = array( 'value'=>$_district['area_id'],'name'=>$_district['name'] );
    		}
    	}
    	if ($json) {
            $this->getResponse()->setHeader('Content-type', 'application/x-json');
            $this->getResponse()->setBody(json_encode( $json ));
        } else {
            $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
        }
    }
}