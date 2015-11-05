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
class Tb_Coolslider_Model_Mysql4_Coolslider_Collection
	  extends Mage_Core_Model_Mysql4_Collection_Abstract{
	  	
		
		public function _construct(){
	        parent::_construct();
	        $this->_init('coolslider/coolslider');
   		 }
		 
		 public function addStoreFilter($store){
	        $this->getSelect()->join(
	            array('coolslider_store_table' => $this->getTable('coolslider_store')),
	            'main_table.slide_id = coolslider_store_table.slide_id',
	            array()
	        )
	        ->where('coolslider_store_table.store_id in (?)', array(0, $store));
	        $this->getSelect()->distinct();
	        return $this;
    	}
		
	  }
?>