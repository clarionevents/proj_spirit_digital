<?php
class Clarion_Seller_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'clarion_seller_carrier';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');
        /* @var $result Mage_Shipping_Model_Rate_Result */
		$shipping = $this->_getSellerTableShippingRate();
		
		if($shipping->getPrice() != -1){
			 $result->append($this->_getSellerTableShippingRate());
        	
		}else{
			Mage::getSingleton('core/session')->addNotice("Is not possible to calculate shipping prices, please contact the retailer");
			
		}
		
		return $result;
    }

    protected function _getSellerTableShippingRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

        $rate->setCarrier($this->_code);
        /**
         * getConfigData(config_key) returns the configuration value for the
         * carriers/[carrier_code]/[config_key]
         */
        $rate->setCarrierTitle($this->getConfigData('title'));
		
		$cart = Mage::getModel('checkout/cart')->getQuote();
   		$cart_collection = $cart->getAllItems();
		$seller_collector = array();
		$totalshippingprice = 0;
		$seller_ids = array();
		foreach($cart_collection as $product){
				
			//Get seller id from the product
			$seller_product = Mage::getModel('marketplace/product')->getCollection()
																   ->addFilter('mageproductid', $product->getProduct_id());
		   $seller_id = 0;
		   foreach ($seller_product as $item) {
				$seller_id = $item->getUserid();
				break;
			}
		   
		    $seller_exists = false;
			foreach($seller_ids as $item){
				if($item === $seller_id ){
					$seller_exists = true;
				}
			}
		    
			if(!$seller_exists){
				array_push($seller_ids, $seller_id);
			}
		}
		
		
		foreach($seller_ids as $seller){
			//Calculate shipping price for each cart items
			$weight_value = 0;
			$price_value = 0;
			$number_value = 0;
			
			foreach($cart_collection as $prod_item){
				$seller_prod_item = Mage::getModel('marketplace/product')->getCollection()
																   		 ->addFilter('mageproductid', $prod_item->getProduct_id());
			    foreach($seller_prod_item as $item) {
					$prod_seller = $item->getUserid();
					break;
				}
				
			$shipping_collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
															 ->addFilter('mageuserid', $seller_id);
				
			if($prod_seller == $seller){
				$weight_value += $prod_item->getWeight() * $prod_item->getQty();
				$price_value += $prod_item->getPrice() * $prod_item->getQty();
				$number_value += $prod_item->getQty();
			}
			
					
			}
			
			$shipping_collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
														 ->addFilter('mageuserid', $seller)
														 ->setOrder('greater', 'ASC')
														 ->setOrder('weight', 'ASC')
														 ->setOrder('numberofitems', 'ASC')
														 ->setOrder('price', 'ASC');
				$rule_applied = false;
				foreach($shipping_collection as $item){
					
					
					if($item->getType() === "weight"){
							if($item->getGreater() == 1){
								if($weight_value > $item->getWeight()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}else{
								
								if($weight_value <= $item->getWeight()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}
						}elseif($item->getType()  === "price"){
							if($item->getGreater() == 1){
								if($price_value > $item->getPrice()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}else{
								if($price_value <= $item->getPrice()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}
						}elseif($item->getType()  === "number"){
							if($item->getGreater() == 1){
								if($number_value > $item->getNumberofitems()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}else{
								if($number_value <= $item->getNumberofitems()){
									$totalshippingprice += $item->getCost();
									$rule_applied = true;
									break;
								}
							}
						}
			 	
		  			}

					if(!$rule_applied){
						$totalshippingprice = -1;
						break;
					}
			
			}
			
			
		    
		
		
		// Add check for not valid shipping
		// Add check for nochex and figure out if this is applied only for one product when it comes to write the delivery details
		
        $rate->setMethod('shipping_price');
        $rate->setMethodTitle('Shipping price');

        $rate->setPrice($totalshippingprice);
        $rate->setCost(0);

        return $rate;
    }

    public function getAllowedMethods()
    {
        return array(
            'shipping_price' => 'Shipping price',
        );
    }
}