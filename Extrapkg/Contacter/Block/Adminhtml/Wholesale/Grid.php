<?php

class Extrapkg_Contacter_Block_Adminhtml_Wholesale_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('wholesaleGrid');
      $this->setDefaultSort('contacter_id');      
      $this->setDefaultDir('DESC');
      $this->setData("wholesaleGridfilter", array("type"=>6));
      Mage::getSingleton('adminhtml/session')->setData("wholesaleGridfilter", array("type"=>6));
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('contacter/contacter')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('contacter_id', array(
          'header'    => Mage::helper('contacter')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'contacter_id',
      ));
	  
	  $this->addColumn('first_name', array(
          'header'    => Mage::helper('contacter')->__('Inquired Product(s)'),
          'align'     =>'left',
		  'width'     => '150px',
          'index'     => 'first_name',
      ));
	  
	   $this->addColumn('tel', array(
          'header'    => Mage::helper('contacter')->__('Quantity'),
          'align'     =>'left',
		  'width'     => '200px',
          'index'     => 'tel',
      ));
      
	    $this->addColumn('conpany_name', array(
          'header'    => Mage::helper('contacter')->__('Shipping Address'),
          'align'     =>'left',
          'index'     => 'conpany_name',
      ));
	  $this->addColumn('email', array(
          'header'    => Mage::helper('contacter')->__('Contact email'),
          'align'     =>'left',
		  'width'     => '250px',
          'index'     => 'email',
      ));
	  
//      $this->addColumn('business_nuture', array(
//          'header'    => Mage::helper('contacter')->__('Request'),
//          'align'     =>'left',
//          'index'     => 'business_nuture',
//          'type'      => 'options',
//          'options'   => array(
//          	1 => Mage::helper('contacter')->__('Replacement'),
//          	2 => Mage::helper('contacter')->__('Refund'),
//          ),
//      ));
//      
//      $this->addColumn('subject', array(
//          'header'    => Mage::helper('contacter')->__('Reasons for the RMA'),
//          'align'     =>'left',
//          'index'     => 'subject',
//          'type'      => 'options',
//          'options'   => array(
//          	1 => Mage::helper('contacter')->__('Defective'),
//          	2 => Mage::helper('contacter')->__('Incompatible'),
//          	3 => Mage::helper('contacter')->__('Wrong item ordered'),
//          	4 => Mage::helper('contacter')->__('Shipping problems'),
//          	5 => Mage::helper('contacter')->__('Other'),
//          ),
//      ));

      $this->addColumn('content', array(
			'header'    => Mage::helper('contacter')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
      
	    $this->addColumn('created_time', array(
          'header'    => Mage::helper('contacter')->__('Creative time'),
          'align'     =>'left',
		  'type'      => 'datetime',
          'index'     => 'created_time',
      ));
      
      $this->addColumn('type', array(
          'header'    => Mage::helper('contacter')->__('Contact type'),
          'align'     =>'left',          
          'index'     => 'type',
          'type'      => 'options',
          'options'   => array(
              1 => Mage::helper('contacter')->__('Product Enquiry'),
              2 => Mage::helper('contacter')->__('Business Opportunities'),
              3 => Mage::helper('contacter')->__('Corporate Sales'),
              4 => Mage::helper('contacter')->__('Feedback & Comments'),
              5 => Mage::helper('contacter')->__('RMA Request'),
							6 => Mage::helper('contacter')->__('Wholesale'),
          ),
      ));
/*
      $this->addColumn('status', array(
          'header'    => Mage::helper('contacter')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  */
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('contacter')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('contacter')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('contacter')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('contacter')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('contacter_id');
        $this->getMassactionBlock()->setFormFieldName('contacter');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('contacter')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('contacter')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('contacter/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('contacter')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('contacter')->__('Status'),
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