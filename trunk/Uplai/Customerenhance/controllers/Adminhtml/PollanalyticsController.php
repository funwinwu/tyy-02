<?php

class Sunvalley_Customerenhance_Adminhtml_PollanalyticsController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('customerenhance/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('customerenhance/customerenhance')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('customerenhance_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('customerenhance/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('customerenhance/adminhtml_customerenhance_edit'))
				->_addLeft($this->getLayout()->createBlock('customerenhance/adminhtml_customerenhance_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customerenhance')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function voteanalticsAction()
	{
		$this->_initAction()
			->renderLayout();
	}
}