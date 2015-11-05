
<?php
/**
 * @title	   Clarion Productslider Module
 * @Company	   Clarion Events
 * @package    Clarion_Productslider
 * @copyright  Copyright (c) 2015 Clarion Events. (http://www.clarionevents.com)
 * @author 	   Giovanni Capuano <giovanni.capuano@clarionevents.com>
 **/
class Clarion_Productslider_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_List implements Mage_Widget_Block_Interface {
		
	protected function _beforeToHtml(){
		parent::_beforeToHtml();
	}
	protected function _prepareLayout(){
		$this->getLayout()->getBlock('head')->addCss('css/flexslider.css');

		$this->getLayout()->getBlock('head')->addItem('js','flexslider/jquery.flexslider-min.js');
		
		parent::_prepareLayout();
	}
}