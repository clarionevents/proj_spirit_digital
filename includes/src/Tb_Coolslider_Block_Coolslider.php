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
class Tb_Coolslider_Block_Coolslider
						extends Mage_Core_Block_Template{
	
	public function _construct(){
        $this->addData(array(
            'cache_lifetime'    => 21600,
            'cache_tags'        => array('coolslider_cache')
        ));		
	}


    /**
     * Retrieve and set js slider configurations
     *
     * @return  array
     */
	public function getSettings(){
		$settings = array();

        if(Mage::helper('coolslider')->containerWidth()){
            $settings['width'] = Mage::helper('coolslider')->containerWidth().'px';
        }else{
            $settings['width'] = '100%';
        }

        if(Mage::helper('coolslider')->containerHeight()){
            $settings['height'] = Mage::helper('coolslider')->containerHeight().'px';
        }else{
            $settings['height'] = '300px';
        }
		
		if(Mage::helper('coolslider')->pagination()){
			$settings['pagination'] = 'true';
		}else{
			$settings['pagination'] = 'false';
		}

        if(Mage::helper('coolslider')->pauseOnHover()){
            $settings['pauseOnHover'] = "true";
        }else{
            $settings['pauseOnHover'] = "false";
        }
		
		if(Mage::helper('coolslider')->navigationArrows()){
			$settings['navigation'] = "true";
		}else{
			$settings['navigation'] = "false";
		}

        if(Mage::helper('coolslider')->duration()){
            $settings['pauseTime'] = (int)Mage::helper('coolslider')->duration();
        }else{
            $settings['pauseTime'] = 2000;
        }

        if(Mage::helper('coolslider')->boxRows()){
            $settings['boxRows'] = (int)Mage::helper('coolslider')->boxRows();
        }else{
            $settings['boxRows'] = 4;
        }

        if(Mage::helper('coolslider')->boxCols()){
            $settings['boxCols'] = (int)Mage::helper('coolslider')->boxCols();
        }else{
            $settings['boxCols'] = 8;
        }

        if(Mage::helper('coolslider')->Slices()){
            $settings['slices'] = (int)Mage::helper('coolslider')->Slices();
        }else{
            $settings['slices'] = 15;
        }

        if(Mage::helper('coolslider')->transitionEffect()){
        $settings['effect'] = Mage::helper('coolslider')->transitionEffect();
        }else{
            $settings['effect'] = 'random';
        }
		
		if(Mage::helper('coolslider')->autoSlide()){
			$settings['manualAdvance'] = "false";
		}else{
			$settings['manualAdvance'] = "true";
		}

        if(Mage::helper('coolslider')->animationSpeed()){
            $settings['animSpeed'] = Mage::helper('coolslider')->animationSpeed();
        }else{
            $settings['animSpeed'] = 500;
        }

		return $settings;
	}

    /**
     * Retrieve slide collection
     *
     * @return  array $collection
     */

	public function getSlides(){
		$collection = Mage::getModel('coolslider/coolslider')->getCollection();
		$collection = $collection->addFieldToFilter('status', 1);
		$collection->addStoreFilter(Mage::app()->getStore()->getId());
		
		return $collection;
	}

    /**
     * Retrieve content position form configuration settings
     *
     * @return  string
     */
	public function getContentPosition(){
		$x_axis = '';
		$y_axis = '';
		
		$poition = Mage::helper('coolslider')->getContentPosition();
		switch($poition){
			
			case 'center_left': 
				$x_axis = '"left":"0",';
				$y_axis = '"top":"50%"';
				break;
			
			case 'top_left': 
				$x_axis = '"left":"0",';
				$y_axis = '"top":"0"';
				break;
				
			case 'top_right': 
				$x_axis = '"right":"0",';
				$y_axis = '"top":"0"';
				break;
				
			case 'bottom_left': 
				$x_axis = '"left":"0",';
				$y_axis = '"bottom":"0"';
				break;
				
			case 'bottom_right': 
				$x_axis = '"right":"0",';
				$y_axis = '"bottom":"0"';
				break;
				
			case 'center_right': 
				$x_axis = '"right":"0",';
				$y_axis = '"top":"50%"';
				break;
			
		}
		
		return $x_axis.$y_axis;
	}
	
		
}
?>