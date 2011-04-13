<?php
class Extrapkg_Magicimport_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/magicimport?id=15 
    	 *  or
    	 * http://site.com/magicimport/id/15 	
    	 */
    	/* 
		$magicimport_id = $this->getRequest()->getParam('id');

  		if($magicimport_id != null && $magicimport_id != '')	{
			$magicimport = Mage::getModel('magicimport/magicimport')->load($magicimport_id)->getData();
		} else {
			$magicimport = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($magicimport == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$magicimportTable = $resource->getTableName('magicimport');
			
			$select = $read->select()
			   ->from($magicimportTable,array('magicimport_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$magicimport = $read->fetchRow($select);
		}
		Mage::register('magicimport', $magicimport);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}