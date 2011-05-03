<?php
class Uplai_Repay_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/repay?id=15 
    	 *  or
    	 * http://site.com/repay/id/15 	
    	 */
    	/* 
		$repay_id = $this->getRequest()->getParam('id');

  		if($repay_id != null && $repay_id != '')	{
			$repay = Mage::getModel('repay/repay')->load($repay_id)->getData();
		} else {
			$repay = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($repay == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$repayTable = $resource->getTableName('repay');
			
			$select = $read->select()
			   ->from($repayTable,array('repay_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$repay = $read->fetchRow($select);
		}
		Mage::register('repay', $repay);
		*/

		if( Mage::app()->getRequest()->getParam('initialize') && Mage::app()->getRequest()->getParam('initialize') == 96 ){
			Mage::getModel("repay/repay")->initialize();
		}else{
			$this->loadLayout();     
			$this->renderLayout();
		}
    }
}