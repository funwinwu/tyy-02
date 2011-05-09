<?php

class Extrapkg_Magicimport_Adminhtml_MagicimportController extends Mage_Adminhtml_Controller_Action
{

	public function checkLicense( $product_code,$time_check = 0 )
	{
		$encrypt_key = "kenadmin@uplai.comisthebestmagentosolution123";
		
		try{			
			$url = Mage::getBaseUrl();	
			$license = '';
			$checkable = false;
			$licent_file_1 = Mage::getBaseDir().DS.'license'.DS.$product_code;
			$licent_file_2 = Mage::getBaseDir().DS.'var'.DS.'license'.DS.$product_code;
			$licent_file_check = Mage::getBaseDir().DS.'var'.DS.'license'.DS.$product_code.'.check';
			
			$client = new SoapClient(null, array(
					'location' => "http://license.uplai.com" . "/api.php",
					'uri' => "http://license.uplai.com"  ));
			//load licesne		
			if( file_exists( $licent_file_1 ) ){
				$license = file_get_contents( $licent_file_1 );			
			}else if( file_exists( $licent_file_2 ) ){
				$license = file_get_contents( $licent_file_2 );			
			}
			
			if(!empty( $license )){
				preg_match_all('/^https?:\/+([^\/]*)\/?/i',$url,$result);
				if( $license == md5($result[1][0].$product_code.$encrypt_key))
					return;
			}
			
			!is_dir( Mage::getBaseDir().DS.'var'.DS.'license' ) && mkdir( Mage::getBaseDir().DS.'var'.DS.'license',0777 );
			
			if( file_exists( $licent_file_check ) ){
				$mtime = filemtime(  $licent_file_check  );
				if( time()-$mtime >= $time_check)
					$checkable = true;
			}else
				$checkable = true;			
			
			if( $checkable ){
				$dt = $client->checkLicense($license,$url,$product_code);
				touch( $licent_file_check );
				if( !$dt ){
					$dt = $client->applyLicense($url,$product_code);	
					file_put_contents( $licent_file_2,$dt );
					
					echo "<script type=\"text/javascript\">\n";
					echo "document.location=\"http://license.uplai.com/copyright.php?license={$dt}\";";
					echo "</script>\n";
					exit();
				}	
			}
		}catch( Exception $e ){
			echo "<script type=\"text/javascript\">\n";
			echo "document.location=\"http://license.uplai.com/copyright.php?product_code={$product_code}\";";
			echo "</script>\n";
			exit();
		}
	}
	
	protected function _initAction() {
		$this->checkLicense('magicimport',60);
		$this->loadLayout()
			->_setActiveMenu('magicimport/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Import Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('magicimport/magicimport')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('magicimport_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('magicimport/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Import Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit'))
				->_addLeft($this->getLayout()->createBlock('magicimport/adminhtml_magicimport_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magicimport')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() 
	{
	
		$success_import = 0; // record how many product have been import.
		$failed_import = 0; // record how many product have been import.
		
		if ($data = $this->getRequest()->getPost()) {
		
			$model = Mage::getModel('magicimport/magicimport');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
			if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
				$model->setCreatedTime(now())
						->setUpdateTime(now());
			} else {
				$model->setUpdateTime(now());
			}	
			
			
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					ini_set("max_execution_time",7200);
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('csv','xls'));
					$uploader->setAllowRenameFiles(false);					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					$model->setData( 'filename',$_FILES['filename']['name'] );
				} catch (Exception $e) {		      
		        }	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
			$model->save();
			
			try {
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				$this->_redirect('*/*/edit', array('id' => $model->getId()));
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magicimport')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
	
	public function getDataFromXLSAction()
	{
		if( $this->getRequest()->getParam('id') > 0 ) {
			$model = Mage::getModel('magicimport/magicimport')->load($this->getRequest()->getParam('id'));
			$path = Mage::getBaseDir('media') . DS ;
			if( $model->getData('data_rows')<=0 && file_exists( $path.$model->getData('filename') ) ){
				try {
					//Mage::getModel("magicimport/magicimport_magicdata")->deleteByImportId( $model->getId() );
					//load xls libary				
					set_include_path(get_include_path() . PATH_SEPARATOR . '/lib/PHPExcel/');
					$objReader = PHPExcel_IOFactory::createReader('Excel5');
					$objPHPExcel = $objReader->load($path.$model->getData('filename'));
					//configuration fields.					
					//$fied_set = array( 'sku','attribute_set_id','weight','name','description','short_description','price','tier_price','category_ids','is_in_stock','qty','meta_keyword','meta_description','meta_title','image' );
					$fied_set = array();
					$product_array = array();
					$product_counter = 0;	
					$field_count = 0;
					
					foreach( $objPHPExcel->getActiveSheet()->getRowIterator() as $row ){						
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,  
						$item_product = array();
						
						$cell_index = 0;
						foreach ($cellIterator as $cell) {
							if( ( $field_count > 0 && $cell_index++ < $field_count ) or $field_count == 0 )
								$item_product[] = (string)$cell->getValue();
						}
						
						if( $product_counter++ == 0 ){
							$fied_set_bak = $item_product;
							
							foreach( $fied_set_bak as $key => $val ){
								$val = trim( $val );
								if( empty( $val ) ) break;
								else $fied_set[] = $val;
							}
							$field_count = count( $fied_set );
						}else{
							$product_array  = array_combine( $fied_set,$item_product );
							
							if( !$model->exist( $product_array ) ){							
								$model->saveData( $product_array );
							}
						}
					}
					$model->refreshDataTotal();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Item was successfully saved'));
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					//Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
			}

			$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
		}else $this->_redirect('*/*/');
	}
	
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('magicimport/magicimport');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $magicimportIds = $this->getRequest()->getParam('magicimport');
        if(!is_array($magicimportIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($magicimportIds as $magicimportId) {
                    $magicimport = Mage::getModel('magicimport/magicimport')->load($magicimportId);
                    $magicimport->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($magicimportIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $magicimportIds = $this->getRequest()->getParam('magicimport');
        if(!is_array($magicimportIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($magicimportIds as $magicimportId) {
                    $magicimport = Mage::getSingleton('magicimport/magicimport')
                        ->load($magicimportId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($magicimportIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'magicimport.csv';
        $content    = $this->getLayout()->createBlock('magicimport/adminhtml_magicimport_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'magicimport.xml';
        $content    = $this->getLayout()->createBlock('magicimport/adminhtml_magicimport_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	
	public function importAction()
	{
		
		if( $data = $this->getRequest()->getParams() ){
			
			$model = Mage::getModel('magicimport/magicimport')->load( $data['id'] );
			
			if( $model->getData('data_type') == Extrapkg_Magicimport_Model_Magicimport_Magicdata::DATA_TYPE_CATEGORY ){
				$stepping = 1;
			}else $stepping = 10;
			$model->import($stepping);
		
			$model->refreshStat();
			$is_finish =!( $model->getData('data_rows') > ( $model->getData('success') + $model->getData('failed') ) );
			$model->setData('finish',$is_finish);
			$model_data = $model->getData();
			$model_data['content'] = '';
			$model_data['backup'] = '';
			echo json_encode( $model_data );
		}
		exit();
	}
	
	public function restoreAction()
	{		
		if( $data = $this->getRequest()->getParams() ){
			$model = Mage::getModel('magicimport/magicimport')->load( $data['id'] );
			if( $model->restore() ){
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Restore successfull'));
			}else{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Restore failed'));
			}
		}
		
		$this->_redirect('*/*/edit', array('id' => $model->getId()));
	}
	
	public function restoreItemAction()
	{
		if( $data = $this->getRequest()->getParams() ){
			$model = Mage::getModel('magicimport/magicimport_magicdata')->load( $data['id'] );
			if( $model->restore() ){
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Restore successfull'));
			}else{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magicimport')->__('Restore failed'));
			}
		}
		
		$this->_redirect('*/*/edit', array('id' => $model->getMagicimportId()));
	}
}