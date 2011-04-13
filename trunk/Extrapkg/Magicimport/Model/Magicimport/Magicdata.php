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

class Extrapkg_Magicimport_Model_Magicimport_Magicdata extends Extrapkg_Magicimport_Model_Magicimport_Abstract
{
    protected function _construct()
    {
        parent::_construct();
    	$this->_init('magicimport/magicimport_magicdata');
    }
	
	
	const DATA_TYPE_PRODUCT = 1;
    const DATA_TYPE_CATEGORY = 2;
	
	public function factory( $data_type )
    {
        if( self::DATA_TYPE_PRODUCT == $data_type ){
			return Mage::getModel("magicimport/magicimport_product");
		}else if( self::DATA_TYPE_CATEGORY == $data_type ){
			return Mage::getModel("magicimport/magicimport_category");
		}
    }
}