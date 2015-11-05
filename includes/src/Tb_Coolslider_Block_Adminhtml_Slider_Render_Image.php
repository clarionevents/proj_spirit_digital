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
class Tb_Coolslider_Block_Adminhtml_Slider_Render_Image
	  extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	  	
   public function render(Varien_Object $row){
   		$rowId = $row->getId();
		$image = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$row->getData($this->getColumn()->getIndex());
		if(fopen($image,"r")){
		
        $html = '<img id="' . $this->getColumn()->getId() .$rowId. '" src="'.$image. '" width="45" height="45"';
        $html .= '/>';
        return $html;
		}else{
			return NULL;
		}
    }
}
?>