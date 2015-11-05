<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class Webkul_Customoption_CustomoptionController extends Mage_Customer_AccountController
{
	public function deletecustomAction()	{		
		$id= $this->getRequest()->getParams('id');	
		$set=Mage::getModel('catalog/product_option')->load($id['id'])->delete();	
	}
}		
  