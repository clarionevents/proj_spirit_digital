<?php
  
class Clarion_Seller_Model_Observer
{
    
     /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;
 
    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the 
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveCustomerNewTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
             
         
            try {
                /**
                 * Perform any actions you want here
                 *
                 */
                
                //Get user data
                $account = $this->_getRequest()->getPost('account');
				
				$seller_id=Mage::registry('current_customer')->getId();
				$collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
															 ->addFilter('mageuserid', $seller_id)
															 ->setPageSize(1);
			 	 foreach($collection as $item){
				 	$existing_shipping_csv = $item->getShippingcsv();
				 }
				
                $shipping_type =  $this->_getRequest()->getPost('shipping_type');
				$shipping_csv =  $this->_getRequest()->getPost('shipping_csv');
				
				
				if($existing_shipping_csv !== $shipping_csv){
				
					$flag=true;
					
    				$csv=new Varien_File_Csv();
					$file='var/import/shipping/' . $shipping_csv;
					$shipping_file=$csv->getData($file);
					
					// open database table
					
					
					if(isset($shipping_type) && ($shipping_csv !== '')){
						
						
						$collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
																					 ->addFilter('mageuserid', $seller_id);
																					 
					 	foreach($collection as $rule){
					 		$rule->delete();
					 	}
						foreach($shipping_file as $data)
						{
							$shipping_table = Mage::getModel('clarion_seller/shippingprices');	
	    					if($flag) { $flag = false; continue; }
								$shipping_table->setData('mageuserid', $seller_id);
						      	$shipping_table->setData('shippingcsv', $shipping_csv);
								
								if($shipping_type == 'weight'){
							      	$shipping_table->setData('type', 'weight');
									$shipping_table->setData('weight', $data[0]);
									$shipping_table->setData('greater', $data[1]);
									$shipping_table->setData('cost', $data[2]);
								}
								
								if($shipping_type == 'price'){
							      	$shipping_table->setData('type', 'price');
									$shipping_table->setData('price', $data[0]);
									$shipping_table->setData('greater', $data[1]);
									$shipping_table->setData('cost', $data[2]);
								}

								
								if($shipping_type == 'number'){
							      	$shipping_table->setData('type', 'number');
									$shipping_table->setData('numberofitems', $data[0]);
									$shipping_table->setData('greater', $data[1]);
									$shipping_table->setData('cost', $data[2]);
								}


								$shipping_table->save();
							}
					}
				}else{
					Mage::getSingleton('core/session')->addNotice('The shipping csv file has the same name as existing one, if you want to change the shipping rules for this seller, please use another name than "' . $shipping_csv . '"');
				}
		}
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }
 }
      
  
     
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

}