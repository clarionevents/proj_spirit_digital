<?php
/**
 * Tb_Cooslider
 * 
 /****************************************************************************
 *                      MAGENTO EDITION USAGE NOTICE                         *
 ****************************************************************************/
 /* This package designed for Magento Community edition. Author does not provide extension support in case of incorrect edition usage.
 /****************************************************************************
 * @category 	TB
 * @package 	Tb_Cooslider
 * @copyright 	Copyright (c) 2014
 * @license 	http://opensource.org/licenses/OSL-3.0
 */
/**
 */
?>
<?php
class Tb_Cooslider_IndexController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
}
?>