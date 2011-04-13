<?php

class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Edit_Tab_Associateddata_Magicdata_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('magicdataGrid');
	  $this->setMagicImportId( Mage::registry('magicimport_data')->getId() );
      $this->setDefaultSort('magicdata_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('magicimport/magicimport_magicdata')->getCollection()->addFieldToFilter( 'magicimport_id',$this->getMagicImportId() );
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('magicdata_id', array(
          'header'    => Mage::helper('magicimport')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'magicdata_id',
      ));

      $this->addColumn('content', array(
          'header'    => Mage::helper('magicimport')->__('Data'),
          'align'     =>'left',
		  'width'     => '300px',
          'index'     => 'content',
      ));
	  
	  $this->addColumn('affected_entites', array(
          'header'    => Mage::helper('magicimport')->__('Affected entities IDs'),
          'align'     =>'left',
		  'width'     => '80px',
          'index'     => 'affected_entites',
      ));
	  
	  	  
	  $this->addColumn('status', array(
          'header'    => Mage::helper('magicimport')->__('Status'),
          'align'     =>'left',          
          'index'     => 'status',
          'type'      => 'options',
		  'width'     => '80px',
          'options'   => Extrapkg_Magicimport_Model_Datastatus::getOptionArray(),
      ));
	 
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('magicimport')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(                                  
                    array(
                        'caption'   => Mage::helper('magicimport')->__('Restore'),
                        'url'       => array('base'=> '*/*/restoreItem'),
                        'field'     => 'id',
						'confirm'  => Mage::helper('magicimport')->__('Are you sure to restore?')
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
				
	  
      return parent::_prepareColumns();
  }
}