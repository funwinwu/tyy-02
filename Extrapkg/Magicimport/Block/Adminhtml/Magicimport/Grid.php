<?php

class Extrapkg_Magicimport_Block_Adminhtml_Magicimport_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('magicimportGrid');
      $this->setDefaultSort('magicimport_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('magicimport/magicimport')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('magicimport_id', array(
          'header'    => Mage::helper('magicimport')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'magicimport_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('magicimport')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('magicimport')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */
		$this->addColumn('data_rows', array(
          'header'    => Mage::helper('magicimport')->__('Data rows'),
          'align'     =>'right',
          'index'     => 'data_rows',
      ));
      $this->addColumn('success', array(
          'header'    => Mage::helper('magicimport')->__('Successful import or update'),
          'align'     =>'right',
          'index'     => 'success',
      ));
      
     	  
	  $this->addColumn('failed', array(
          'header'    => Mage::helper('magicimport')->__('Import failed'),
          'align'     =>'right',
          'index'     => 'failed',
      ));
      
      $this->addColumn('import_type', array(
          'header'    => Mage::helper('magicimport')->__('Action type'),
          'align'     =>'left',          
          'index'     => 'import_type',
          'type'      => 'options',
          'options'   => Extrapkg_Magicimport_Model_Importtype::getOptionArray(),
      ));
      
       $this->addColumn('data_type', array(
          'header'    => Mage::helper('magicimport')->__('Data type'),
          'align'     =>'left',          
          'index'     => 'data_type',
          'type'      => 'options',
          'options'   => Extrapkg_Magicimport_Model_Datatype::getOptionArray(),
      ));
	  
	  $this->addColumn('main_status', array(
          'header'    => Mage::helper('magicimport')->__('Status'),
          'align'     =>'left',          
          'index'     => 'main_status',
          'type'      => 'options',
		  'width'     => '100px',
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
                        'caption'   => Mage::helper('magicimport')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ), 
                    array(
                        'caption'   => Mage::helper('magicimport')->__('Download file'),
                        'url'       => array('base'=> '*/*/download'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => Mage::helper('magicimport')->__('Restore'),
                        'url'       => array('base'=> '*/*/restore'),
                        'field'     => 'id',
						'confirm'  => Mage::helper('magicimport')->__('Are you sure to restore?')
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('magicimport')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('magicimport')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('magicimport_id');
        $this->getMassactionBlock()->setFormFieldName('magicimport');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('magicimport')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('magicimport')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('magicimport/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('magicimport')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('magicimport')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}