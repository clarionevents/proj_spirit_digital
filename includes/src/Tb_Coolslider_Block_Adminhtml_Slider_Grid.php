<?php
/**
 * Tb_Coolslider
 * 
 /****************************************************************************
 *                      MAGENTO EDITION USAGE NOTICE                         *
 ****************************************************************************/
 /* This package designed for Magento Community edition. Author does not provide extension support in case of incorrect edition usage.
 /****************************************************************************
 * @category 	TB
 * @package 	Tb_Coolslider
 * @copyright 	Copyright (c) 2014
 * @license 	http://opensource.org/licenses/OSL-3.0
 */
/**
 */
?>
<?php
class Tb_Coolslider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	
	public function __construct()
    {
        parent::__construct();
        $this->setId('coolsliderGrid');
        $this->setDefaultSort('slide_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
  
  
  	protected function _prepareCollection(){
        $collection = Mage::getModel('coolslider/coolslider')->getCollection();
        foreach($collection as $item) {
            $stores = $this->lookupStoreIds($item->getId());
            $item->setData('store_id', $stores);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
	
	public function lookupStoreIds($objectId)
    {
        $adapter = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tableName = Mage::getSingleton('core/resource')->getTableName('cool_slider_store');
        $select  = $adapter->select()
            ->from($tableName, 'store_id')
            ->where('slide_id = ?',(int)$objectId);

        return $adapter->fetchCol($select);
    }
	
	
	protected function _prepareColumns()
    {
        $this->addColumn('slide_id', array(
          'header'    => Mage::helper('coolslider')->__('ID'),
          'align'     =>'right',
          'width'     => '10px',
          'index'     => 'slide_id',
        ));
		
		$this->addColumn('title', array(
          'header'    => Mage::helper('coolslider')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
          'width'     => '150px',
        ));
 
          
        $this->addColumn('url', array(
            'header'    => Mage::helper('coolslider')->__('Url'),
            'width'     => '150px',
            'index'     => 'url',
        ));
		
		if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('coolslider')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }
		
		
		$this->addColumn('image', array(
          'header'    => Mage::helper('coolslider')->__('Image'),
          'align'     =>'left',
          'type'      => 'image',
          'index'     => 'image',
          'renderer' => 'coolslider/adminhtml_slider_render_image',
          'filter'    => false,
		  'width'     => '100px',
          'sortable'  => false,
));
		
		$this->addColumn('status', array(
            'header'    => Mage::helper('coolslider')->__('Status'),
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('coolslider')->__('Enabled'),
                0 => Mage::helper('coolslider')->__('Disabled')
            ),
        ));
		
		$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('coolslider')->__('Action'),
                'width'     => '60',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('coolslider')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		return parent::_prepareColumns();
	}
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	
	protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
	
	 
	 protected function _prepareMassaction()
    {
        $this->setMassactionIdField('slide_id');
        $this->getMassactionBlock()->setFormFieldName('coolslider');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('coolslider')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('coolslider')->__('Are you sure?')
        ));

        $statuses = array(
              1 => Mage::helper('coolslider')->__('Enabled'),
              0 => Mage::helper('coolslider')->__('Disabled')
        );
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('coolslider')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('coolslider')->__('Status'),
                         'values' => array(
                                            1 => 'Enabled',
                                            0 => 'Disabled',
                                        )
                     )
             )
        ));
        return $this;
    }
		
}
?>