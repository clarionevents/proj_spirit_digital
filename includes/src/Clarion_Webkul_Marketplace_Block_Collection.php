<?php
/**
 * @title	   Clarion Webkul Marketplace Module adapt
 * @Company	   Clarion Events
 * @package    Clarion_Webkul
 * @author 	   Giovanni Capuano <giovanni.capuano@clarionevents.com>
 **/
class Clarion_Webkul_Marketplace_Block_Collection  extends Mage_Catalog_Block_Product_Abstract
{
	//protected $_defaultToolbarBlock = 'catalog/product_list_toolbar';
	public function __construct(){		
		parent::__construct();
		if(array_key_exists('c', $_GET)){	
    	$cate = Mage::getModel('catalog/category')->load($_GET["c"]);
	    }	
    	$partner=$this->getProfileDetail();
        $productname=$this->getRequest()->getParam('name');
		$querydata = Mage::getModel('marketplace/product')->getCollection()
				->addFieldToFilter('userid', array('eq' => $partner->getmageuserid()))
				->addFieldToFilter('status', array('neq' => 2))
				->setOrder('mageproductid');
		$rowdata=array();		
		foreach ($querydata as  $value) {
				$rowdata[] = $value->getMageproductid();
		}
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addAttributeToSelect('*');
		
		if(array_key_exists('c', $_GET)){
			$collection->addCategoryFilter($cate);
		}
            $collection->addAttributeToFilter('entity_id', array('in' => $rowdata));
			if((Mage::helper('core')->isModuleEnabled('Webkul_Webkulsearch')) && ($productname!='')){
                $collection->addFieldToFilter('name', array('like' => '%'.$productname.'%'));   
            }
    		$this->setCollection($collection);	
    	}
    	
  }