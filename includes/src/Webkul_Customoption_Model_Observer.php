<?php
Class Webkul_Customoption_Model_Observer
{
	public function mpcustomoptionsetdata($observer){
		$wholedata = $observer->getData();
		$lastId = $wholedata['id'];
		$currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
		$selectoptions=$wholedata['selectoptions'];
		$magentoProductModel = Mage::getModel('catalog/product')->load($lastId);
		$baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
		$allowedCurrencies = Mage::getModel('directory/currency')->getConfigAllowCurrencies(); 
		$rates = Mage::getModel('directory/currency')->getCurrencyRates($baseCurrencyCode, array_values($allowedCurrencies));
		$data=$wholedata['options'];
		foreach($wholedata['removeoptions'] as $id){
			$set=Mage::getModel('catalog/product_option')->load($id)->delete();
		}
		foreach($data as $options){	
			$values=array();
			$select=array('multiple','drop_down','checkbox','radio');
			if(in_array($options['type'],$select)){
				foreach($selectoptions[$options['customoptindex']] as $optionsdata){
					if($optionsdata['price_type'] == 'fixed')
						$price = $optionsdata['price']/$rates[$currentCurrencyCode];
					else
						$price = $optionsdata['price'];
					array_push($values,array(
							'title'=>$optionsdata['title'],
							'price'=>$price,
							'price_type'=>$optionsdata['price_type'],
							'sku'=>$optionsdata['sku'],
							'sort_order'=>$optionsdata['sort_order'],
							'is_delete'=>0,
							'option_type_id'=>-1,
						)
					);
				}
				//$values=$selectoptions[$options['customoptindex']];
			}
			if($options['price_type'] == 'fixed')		
				$price = $options['price']/$rates[$currentCurrencyCode];
			else
				$price = $options['price'];
			$optionData = array(						
							'is_require'    => $options['is_require'],
							'previous_group'=> '',	
							'title'         => $options['title'],	
							'type'          => $options['type'], //field,area,checkbox,radio etc drop_down	
							'price_type'    => $options['price_type'],	
							'price'         => $price,		
							'sort_order'    => $options['sort_order'],
							'sku'			=> $options['sku'],
							'file_extension'=> $options['file_extension'],
							'image_size_x'	=> $options['image_size_x'],
							'image_size_y'	=> $options['image_size_y'],
							'max_characters'=> $options['max_characters'],
							'values'	    => $values,					
						);							
			$magentoProductModel->setHasOptions(1)->save();	
			$option = Mage::getModel('catalog/product_option')
							->setProductId($magentoProductModel->getId())
							->setStoreId($magentoProductModel->getStoreId())
							->addData($optionData);		
			$option->save();	
			$magentoProductModel->addOption($option);	
			$magentoProductModel->save();
		}
	}	
}