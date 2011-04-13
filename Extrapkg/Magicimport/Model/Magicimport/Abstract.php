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

class Extrapkg_Magicimport_Model_Magicimport_Abstract extends Mage_Core_Model_Abstract
{
    
	
	public function deleteByImportId( $import_id )
	{
		$dbh = Mage::getSingleton('core/resource')->getConnection('core_write');
		$dbh->beginTransaction();
        try {
			$dbh->delete('magicdata', "magicimport_id=$import_id");
		}catch (Exception $e){			
            $dbh->rollBack();
            return false;
        }
        $dbh->commit();
		return true;
	}
	
	public function backup( $data,$change_field )
	{
		$org_data = array();
		!isset( $data['id'] ) && $data['id'] = $data['entity_id'];
		
		if( isset( $data['id'] ) ){
			$org_data['id'] = $data['id'];
			$backup = array();		
			
			if( $this->getData( 'backup' ) )
				$backup = json_decode( $this->getData( 'backup' ),true );
			
			foreach( $change_field as $field_key ){
				if( isset( $data[ $field_key ] ) ){
					$org_data[ $field_key ] = $data[ $field_key ];
				}
			}
			
			$backup[] = $org_data;		
			$this->setData( 'backup',json_encode( $backup ) );
			
			$this->save();
		}
		return;
	}
	
	
	public function restore()
	{
		return;
	}
	
	
	protected function prepareData()
	{
		$product_data = $this->_removeEmptyField( json_decode( $this->getData('content') ) );
		return $product_data;
	}
	
	public function add()
	{
		return;
	}
	
	public function updatePricise()
	{		
		return;
	}
	
	protected function _removeEmptyField( $data )
    {
    	$new_data = array();
    	foreach( $data as $key => $var ){
    		if( !empty( $key ) && !empty( $var ) ){
    			$new_data[ $key ] = addslashes($var);
    		}
    	}
		
    	return $new_data;   	
    }	
	
	public function getMagicImport()
	{
		static $cache;
		if( $this->getData('magicimport_id') ){
			if( !isset( $cache[$this->getData('magicimport_id')] ) ){
				$cache[$this->getData('magicimport_id')] = Mage::getModel("magicimport/magicimport")->load($this->getData('magicimport_id'));
			}
			return $cache[$this->getData('magicimport_id')];
		}else return '';
	}
}