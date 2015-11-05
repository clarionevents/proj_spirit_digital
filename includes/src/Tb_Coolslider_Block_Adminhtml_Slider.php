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
class Tb_Coolslider_Block_Adminhtml_Slider extends
	  								Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct(){
		$this->_controller = 'adminhtml_slider';
        $this->_blockGroup = 'coolslider';
       	$this->_headerText = Mage::helper('coolslider')->__('Coolslider manager');
        $this->_addButtonLabel = Mage::helper('coolslider')->__('Add New slide');
        parent::__construct();
    }							
										
}
?>