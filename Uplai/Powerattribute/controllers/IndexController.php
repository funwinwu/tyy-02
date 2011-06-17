<?php
class Uplai_Powerattribute_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/powerattribute?id=15 
    	 *  or
    	 * http://site.com/powerattribute/id/15 	
    	 */
    	/* 
		$powerattribute_id = $this->getRequest()->getParam('id');

  		if($powerattribute_id != null && $powerattribute_id != '')	{
			$powerattribute = Mage::getModel('powerattribute/powerattribute')->load($powerattribute_id)->getData();
		} else {
			$powerattribute = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($powerattribute == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$powerattributeTable = $resource->getTableName('powerattribute');
			
			$select = $read->select()
			   ->from($powerattributeTable,array('powerattribute_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$powerattribute = $read->fetchRow($select);
		}
		Mage::register('powerattribute', $powerattribute);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}