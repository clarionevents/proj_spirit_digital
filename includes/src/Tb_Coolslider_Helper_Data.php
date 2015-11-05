<?php
/**
 * Tb_Carouslslide
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
class Tb_Coolslider_Helper_Data 
	  				extends Mage_Core_Helper_Abstract{
		
	public function isEnabled()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/enable');
    }

	public function isIncludeJqueryLib()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/jquery_lib');
    }

    public function containerWidth()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/width');
    }

    public function containerHeight()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/height');
    }

	public function pagination()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/pagination');
    }

	public function pauseOnHover()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/pause');
    }

	public function navigationArrows()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/navigation');
    }

	public function duration()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/duration');
    }

	public function transitionEffect()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/effects');
    }

    public function boxRows()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/boxrows');
    }

    public function boxCols()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/boxcols');
    }

    public function Slices()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/slices');
    }

	public function autoSlide()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/auto');
    }

    public function animationSpeed()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/animspeed');
    }

    public function getContentPosition()
    {
        return Mage::getStoreConfig('coolsliderset/general_settings/position');
    }
	
	
	}
?>