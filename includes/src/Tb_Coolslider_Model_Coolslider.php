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
class Tb_Coolslider_Model_Coolslider extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('coolslider/coolslider');
    }
	
	
	// Reset all model data
	public function reset()
    {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;
        return $this;
    }

}
?>