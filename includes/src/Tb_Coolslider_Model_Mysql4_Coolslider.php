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
class Tb_Coolslider_Model_Mysql4_Coolslider extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct(){
        $this->_init('coolslider/coolslider', 'slide_id');
    }
	
	protected function _beforeSave(Mage_Core_Model_Abstract $object){
    }
	
	protected function _afterSave(Mage_Core_Model_Abstract $object){
        $condition = $this->_getWriteAdapter()->quoteInto('slide_id = ?', $object->getId());
		
        $this->_getWriteAdapter()->delete($this->getTable('coolslider_store'), $condition);
        if (count($object->getData('stores')) && (!in_array(0, (array)$object->getData('stores')))) {
            foreach ((array)$object->getData('stores') as $store) {
                $data = array();
                $data['slide_id'] = $object->getId();
                $data['store_id'] = $store;
                $this->_getWriteAdapter()->insert($this->getTable('coolslider_store'), $data);
            }
        } else {
            $data = array();
            $data['slide_id'] = $object->getId();
            $data['store_id'] = '0';
            $this->_getWriteAdapter()->insert($this->getTable('coolslider_store'), $data);
        }

        return parent::_afterSave($object);
    }
	
	
	protected function _beforeDelete(Mage_Core_Model_Abstract $object){
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('coolslider_store'), 'slide_id='.$object->getId());
        
    }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object){
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('coolslider_store'))
            ->where('slide_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $stores = array();
            foreach ($data as $row) {
                $stores[] = $row['store_id'];
            }
            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }
	
	
	}
?>