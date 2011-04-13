<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Poll
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Poll answers model
 *
 * @category   Mage
 * @package    Mage_Poll
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Extrapkg_Magicimport_Model_Magicimport_Category extends Extrapkg_Magicimport_Model_Magicimport_Abstract
{
	protected function _construct()
    {
        parent::_construct();
    	$this->_init('magicimport/magicimport_category');
    }
	
	public function restore()
	{
		if( $this->getData( 'backup' ) ){
			$backup = json_decode( $this->getData( 'backup' ),true );
			foreach( $backup as $categoryData ){
				if( !empty( $categoryData['id'] ) ){	
					$category = Mage::getModel("catalog/category")->load( $categoryData['id'] );
				    $category->addData($categoryData);	
					$category->save();
				}
			}
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_RESTORE );
			$this->save();
			return true;
		}else{		
			return false;   
		}
	}
		
	
	public function add()
	{
		$magic_data = $this->prepareData();
		$category = $this->_initCategorySave( $magic_data );
		
		if( $category->save()  ){ //for stat		
			Mage::dispatchEvent('catalog_category_prepare_save', array(
                'category' => $category,
                'request' => $this
            ));	
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
			$this->setData( 'affected_entites',1 );
		}else{
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
		}
	}
	
	public function update()
	{
		$magic_data = $this->prepareData();
		$category = $this->_initCategorySave( $magic_data );
		
		if( $category->save()  ){ //for stat		
			Mage::dispatchEvent('catalog_category_prepare_save', array(
                'category' => $category,
                'request' => $this
            ));	
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_FINISH );
			$this->setData( 'affected_entites',1 );
		}else{
			$this->setStatus( Extrapkg_Magicimport_Model_Datastatus::TYPE_CANCELED );
		}
	}
	
	protected function _initCategorySave( $magic_data )
    {
        !isset( $magic_data['display_mode'] ) && $magic_data['display_mode'] = 'PRODUCTS';
        !isset( $magic_data['is_anchor'] ) && $magic_data['is_anchor'] = '1';
        !isset( $magic_data['custom_design_apply'] ) && $magic_data['custom_design_apply'] = '1';
		
		if( isset( $magic_data['id'] ) ){
			$category = Mage::getModel("catalog/category")->load( $magic_data['id'] );
			$this->backup( $category->getData(),array_keys( $magic_data ) ); // backup
		}else{
			$category = Mage::getModel("catalog/category");
		}
		
		//set parent id;
		if( isset( $magic_data['parent_id'] ) ){
			$parentId = $magic_data['parent_id'];			
		}else{
			if( isset( $magic_data['store_id'] ) ){
				$parentId = Mage::app()->getStore($storeId)->getRootCategoryId();
			}else{
				$parentId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
			}
		}
		
		$parentCategory = Mage::getModel('catalog/category')->load($parentId);
		$category_path = $parentCategory->getPath();
		if( $category->getId() )
			$category_path .= '/'.$category->getId();
        $category->setPath($category_path);
		
		$category->setAttributeSetId($category->getDefaultAttributeSetId());
		$category->addData($magic_data);
		return $category;
    }
}