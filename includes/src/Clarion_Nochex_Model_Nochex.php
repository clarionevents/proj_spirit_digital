<?php

/**
 * @title	   Clarion Nochex Checkout Module
 * @Company	   Clarion Events
 * @package    Clarion_Nochex
 * @copyright  Copyright (c) 2015 Clarion Events. (http://www.clarionevents.com)
 * @author 	   Giovanni Capuano <giovanni.capuano@clarionevents.com>
 **/

class Clarion_Nochex_Model_Nochex extends Mage_Payment_Model_Method_Abstract
{

	protected $_isGateway = true;
    protected $_canAuthorize = true;
	protected $_canUseCheckout = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = false;
    protected $_canVoid  = false;
    protected $_canUseInternal = false;
    protected $_canUseForMultishipping = true;
    protected $_canSaveCc = true;

	protected $_code = 'nochex';
	protected $_infoBlockType = 'NochexLTD_Nochex_Block_Info';
	protected $_formBlocktype = 'NochexLTD_Nochex_Block_Form';


	public function authorize(Varien_Object $payment, $amount)
	{
		return $this;
	}


	public function capture(Varien_Object $payment, $amount)
    {
		return $this;
	}


    public function getSession()
    {
        return Mage::getSingleton('nochex/session');
    }
	

	public function getNewOrderStatus()
	{
		$state = $this->getConfigData("new_order_status");
        if (!$state) {
		    Mage_Sales_Model_Order::STATE_PENDING; 
        }
        return $state;
	}
	
		public function getSuccessfulOrderStatus()
	{
        $state = $this->getConfigData("success_order_status");
        if (!$state) {
		    Mage_Sales_Model_Order::STATE_PROCESSING; 
        }
        return $state;
	}


    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
	
	
	public function createFormBlock($name)
    {
      $block = $this->getLayout()->createBlock('nochex/form', $name)
           ->setMethod('nochex')
           ->setPayment($this->getPayment())
           ->setTemplate('nochexltd/nochex/form.phtml');
      return $block;
   }

	
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('nochex/nochex/redirect');
    }


	public function getNochexUrl()
    {
         $url='https://secure.nochex.com';
         return $url;
    }
	
	
	public function getOrder()
    {
        $order = Mage::getModel('sales/order');
        $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
        return $order;
    }
	
		// gets the cancel order status
		public function getCancelOrderStatus()
		{
        $state = $this->getConfigData("cancel_order_status");
		if (!$state) {
		$state = Mage_Sales_Model_Order::STATE_CANCELED; 
		}
        return $state;
		}


	public function writeDebug($DebugData){
	// Calls the configuration information about a control in the module config. 
	//$nochex_debug = Configuration::get('NOCHEX_APC_DEBUG');
	// If the control nochex_debug has been checked in the module config, then it will use data sent and received in this function which will write to the nochex_debug file
		if (1 == $this->getConfigData("nochexDebug")){
		// Receives and stores the Date and Time
		$debug_TimeDate = date("m/d/Y h:i:s a", time());
		// Puts together, Date and Time, as well as information in regards to information that has been received.
		$stringData = "\n Time and Date: " . $debug_TimeDate . "... " . $DebugData ."... ";
		 // Try - Catch in case any errors occur when writing to nochex_debug file.
			try
			{
			// Variable with the name of the debug file.
				$debugging = "nochex_debug.txt";
			// variable which will open the nochex_debug file, or if it cannot open then an error message will be made.
				$f = fopen($debugging, 'a') or die("File can't open");
			// Open and write data to the nochex_debug file.
			$ret = fwrite($f, $stringData);
			// Incase there is no data being shown or written then an error will be produced.
			if ($ret === false)
			die("Fwrite failed");
			
				// Closes the open file.
				fclose($f)or die("File not close");
			} 
			//If a problem or something doesn't work, then the catch will produce an email which will send an error message.
			catch(Exception $e)
			{
			mail($this->email, "Debug Check Error Message", $e->getMessage());
			}
		}
	}


	public function getNochexCheckoutFormFields()
    {
	
	
		// Gets the data of the order from sales/order and stores in the variable $order
		$order = Mage::getModel('sales/order');
		// Calls the loadbyincrement function which goes and collects the most recent orderID
        $order->loadByIncrementId($this->getCheckout()->getLastRealOrderId());
		
		// Gets the data model for nochex/nochex, and stores as pay details.
		$paydetails = Mage::getModel('nochex/nochex');
		//The variable shipping address stores the shipping details from the latest order in paydetails
		$shippingaddress = $paydetails->getOrder()->getShippingAddress();
		
		//The variable billing address stores the shipping details from the latest order in paydetails
		$billingaddress= $paydetails->getOrder()->getBillingAddress();
		
		
		//The variable country stores the shipping details from the latest order in paydetails
		$countryID= $paydetails->getOrder()->getBillingAddress()->getCountry();
		
		//Stores a session.
		$session = Mage::getSingleton('checkout/session');
		
		$sameSellerCheck = array();
		
		 if($this->getConfigData("xmlCollection") == 1) {

			 $xmlData = "<items>";

			 $seller_ids = array();
			 foreach ($order->getAllVisibleItems() as $item) {
				 $users = Mage::getModel('marketplace/product')->getCollection()->addFieldToFilter('mageproductid', array('eq' => $item->getProductId()));
				 foreach ($users as $record) {
					 $userid = $record->getuserid();
				 }
				 $userEmail = Mage::getModel('customer/customer')->load($userid)->getEmail();
				 $nProduct = Mage::getModel('catalog/product')->load($item->getProductId());
				 // $xmlData .= "<item><id>".$item->getId()."</id><name>".$item->getName()."</name><description>". $item->getName() ."". $item->getDescription()."</description><quantity>".number_format($item->getQtyOrdered(),0)."</quantity><price>".money_format('%.2n', $item->getPrice())."</price></item>";
				 $xmlData .= "<item><id>" . $item->getId() . "</id>";
				 $xmlData .= "<model>" . $nProduct->getSku() . "</model>";
				 $xmlData .= "<description>" . $item->getName() . "" . $item->getDescription() . "</description>";
				 $qtySingleProd = number_format($item->getQtyOrdered(), 0);
				 $xmlData .= "<qty>" . $qtySingleProd . "</qty>";

				 //Apply discount
				 $appliedRuleIds = Mage::getSingleton('checkout/session')->getQuote()->getAppliedRuleIds();
				 $oRule = Mage::getModel('salesrule/rule')->load($appliedRuleIds);
				 $dataRule = $oRule->getData();

				 if ($dataRule['use_auto_generation'] == 0) {
					 $price = $item->getPrice();
					 $discount = 0;
					 if ($item->getDiscountAmount() > 0) {
						 $discount = ($price / 100) * 15;
						 $price = $price - $discount;
					 }
				 }


				 // $xmlData .="<price>". money_format('%.2n', ($item->getPrice() - $item->getDiscountAmount()) ) ."</price>";
				 $xmlData .= "<price>" . $price . "</price>";
				 $xmlData .= "<merchant_id>" . $userEmail . "</merchant_id>";
				 $xmlData .= "</item>";


				 $seller_exists = false;
				 foreach ($seller_ids as $prod_item) {
					 if ($prod_item === $userid) {
						 $seller_exists = true;
					 }
				 }

				 if (!$seller_exists) {

					 array_push($seller_ids, $userid);
				 }

			 }

			 //Calculate discountCoupon
		 if($dataRule['use_auto_generation'] == 1){
			 $discountItems = '';
			 foreach ($seller_ids as $seller) {
				 $price_value = 0;
				 $userEmail = Mage::getModel('customer/customer')->load($seller)->getEmail();
				 $cart_collection = $order->getAllVisibleItems();

				 foreach ($cart_collection as $prod_item) {
					 $seller_prod_item = Mage::getModel('marketplace/product')->getCollection()
						 ->addFilter('mageproductid', $prod_item->getProductId());
					 foreach ($seller_prod_item as $item) {
						 $prod_seller = $item->getUserid();
						 break;
					 }

					 $shipping_collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
						 ->addFilter('mageuserid', $seller);


					 if ($prod_seller == $seller) {
						 $price_value += $prod_item->getPrice() * $prod_item->getQty_ordered();
					 }


					 $discountItems .= createCouponDiscount($price_value, 1, $userEmail);

				 }
			 }
		 }
			 	$xmlData .= $discountItems;

				 $xmlData .= "<delivery>";
		foreach($seller_ids as $seller){
			
			$userEmail = Mage::getModel('customer/customer')->load($seller)->getEmail();
			 
			$xmlData .= "<merchant>";
			$xmlData .="<merchant_id>". $userEmail  ."</merchant_id>";
			// $xmlData .="<price>". ($shippingPrice * number_format($item->getQtyOrdered(),0))  . "</price>";
			
			$xmlData .="<price>". $this->calculateDeliveryPrices($seller, $order) . "</price>";
			
			$xmlData .= "</merchant>";
			
		}
		 
		 // foreach ($order->getAllVisibleItems() as $item) {
		 	// $shippingPrice = 0;	
		 	//get product object
		 	// $users = Mage::getModel('marketplace/product')->getCollection()->addFieldToFilter('mageproductid',array('eq'=>$item->getProductId()));
			// foreach($users as $record){
				// $userid=$record->getuserid();
			// }
			
			// get seller email for payment ID
			// $userEmail = Mage::getModel('customer/customer')->load($userid)->getEmail();
			
			//get delivery prices
			// $mpshippingcharge=Mage::getModel('catalog/product')->load($item->getProductId())->getMpShippingCountryCharge();
					// $totalCountries = explode('/',$mpshippingcharge);
					// foreach($totalCountries as $pric){
					  	// $separateCountry= explode(',',$pric); 
                      	// if (in_array($countryID,  $separateCountry)) {
                           // $shippingPrice = $separateCountry[1];
                      	// }
				    // }
		    foreach ($order->getAllVisibleItems() as $item) {
		    	$users = Mage::getModel('marketplace/product')->getCollection()->addFieldToFilter('mageproductid',array('eq'=>$item->getProductId()));
				foreach($users as $record){
					$userid=$record->getuserid();
				}
		    	$userEmail = Mage::getModel('customer/customer')->load($userid)->getEmail();
				array_push($sameSellerCheck, $userEmail);
		 	}
		 $xmlData .= "</delivery>";
		 $xmlData .= "</items>";
		 
		 $description = "Order created for: " . $this->getCheckout()->getLastRealOrderId();
		 
		 }else{
		
		// Variable for storing a description.
		$description = "";
		
		// Loops through all the information in the order, and stores the description to a related item.
		foreach ($order->getAllVisibleItems() as $item) {
		//$description .= " Product: " . $item->getName() . ". Qty: ". number_format($item->getQtyOrdered(),0) . ". Amount: ". money_format('%.2n', $item->getPrice()) . ". Description: " . $item->getDescription() . ". ";
		$description .= " ". $item->getId() . ".  ". $item->getName() . ".  ". number_format($item->getQtyOrdered(),0) . ". ". money_format('%.2n', $item->getPrice()) . ".  " . $item->getDescription() . ".  ";
		}
		
		}
		 
		$flag = FALSE;
		if(count($sameSellerCheck) > 0){
			foreach($sameSellerCheck as $singleSeller){
				if($singleSeller != $sameSellerCheck[0]){
					$flag = TRUE;
				}
			}
		}
		$merchantID = $sameSellerCheck[0];
		if($flag){
			$merchantID = $this->getConfigData("merchant_id");
		}
		
			$descriptionItems = 'Order Details: - Description Field: ' . $description . '. \n XML Collection Data: ' . $xmlData. '.\n XML Data Collection On (1) / Off (0) - ' . $this->getConfigData("xmlCollection");
			$this->writeDebug($descriptionItems);
			
		// Variable for storing the shipping address details.
		$shipadd = "";
		// Concatanation for adding the shipping address together which is stored in one variable.
		// Shipping address - line 1
		if (strlen($shippingaddress->getStreet(1)) != 0) {
			$shipadd .= $shippingaddress->getStreet(1);
			}
		// Shipping address - line 2
		if (strlen($shippingaddress->getStreet(2)) != 0) {
			$shipadd .= ", " . $shippingaddress->getStreet(2);
			}
		// Shipping address - city.
		//if (strlen($shippingaddress->getCity()) != 0) {
		//	$shipadd .= ", " . $shippingaddress->getCity();
		//	}
		
		// Variable for storing the billing address details.
		$billadd = "";
		// Concatanation for adding the shipping address together which is stored in one variable.
		// Billing address - line 1
		if (strlen($billingaddress->getStreet(1)) != 0) {
			$billadd .= $billingaddress->getStreet(1);
			}
		// Billing Address - line 2
		if (strlen($billingaddress->getStreet(2)) != 0) {
			$billadd .= ", " . $billingaddress->getStreet(2);
			}
		// Billing Address - City
		//if (strlen($billingaddress->getCity()) != 0) {
		//	$billadd .= ", " . $billingaddress->getCity();
		//	}		
			 
			 
			 $addressDetails = 'Order Details: - Billing Address: ' . $billadd . '. Shipping Address: ' . $shipadd;
			$this->writeDebug($addressDetails);
			
			 
			if ($this->getConfigData("postageAmount") == 1) {
			
			$totalAmount = $order->getBaseTotalDue()- $order->getShippingAmount();
			$postage = $order->getShippingAmount();
			
			}else{
			
			$totalAmount = $order->getBaseTotalDue();
			
			}
				
			$amountFields = 'Order Details: - Amount: ' . $totalAmount . '. Postage: ' . $postage . ". Postage Field On (1) / Off (0) - " . $this->getConfigData("postageAmount");
			$this->writeDebug($amountFields);
				
				
        $checkoutparams = array(
            // 'merchant_id' 		=> $this->getConfigData("merchant_id"),
            'merchant_id' 		=> $merchantID,
            'success_url' 		=> Mage::getUrl('checkout/onepage/success'),
            'cancel_url' 		=> Mage::getUrl('nochex/nochex/cancel'),
            'callback_url' 		=> Mage::getUrl('nochex/nochex/apc'),
            'order_id' 			=> $this->getCheckout()->getLastRealOrderId(),
            'billing_fullname' 	=> $billingaddress->getFirstname() . " " . $billingaddress->getLastname(),
            'customer_phone_number' => $billingaddress->getTelephone(),
            'email_address'		=> $order->getCustomerEmail(),
            'billing_address' 	=> $billadd,
			'billing_city' 		=> $billingaddress->getCity(),
            'billing_postcode' 	=> $billingaddress->getPostcode(),
			'delivery_fullname' => $shippingaddress->getFirstname() . " " . $shippingaddress->getLastname(),	
			'delivery_address' 	=> $shipadd,
			'delivery_city' 	=> $shippingaddress->getCity(),
			'delivery_postcode' => $shippingaddress->getPostcode(),
			'amount' 			=> $totalAmount,
			'postage' 			=> $postage,
			'description' 		=> $description,			
			'item_collection' => $xmlData,			
        );
	
	$totalDetails = 'Order Details: - All Fields: '. print_r($checkoutparams, true);
			$this->writeDebug($totalDetails);
	
		// If the hide_billing_details variable to determine whether billing details should be hidden.
		// If the hide_billing_details = true {or 1}, then send the below parameter.
	if ($this->getConfigData("hide_billing_details") == 1) {
	$checkoutparams = array_merge($checkoutparams, array('hide_billing_details' => "true"));
	}
	
	// If statement determines whether test_mode has been selected, then test transactions are performed, otherwise transactions are live.
		if($this->getConfigData("test_mode")){
            $checkoutparams = array_merge($checkoutparams, array('test_transaction' => '100', 'test_success_url' => Mage::getUrl('checkout/onepage/success')));
        }

			$totalDetails = 'Order Details: - All Fields: '. print_r($checkoutparams, true);
			$this->writeDebug($totalDetails);


	$extraFeat = 'Order Details: - Test Mode On (1) / Off (0):'. $this->getConfigData("test_mode") . ', Hide Billing Mode On (1) / Off (0) - '.$this->getConfigData("hide_billing_details") ;
			$this->writeDebug($extraFeat);


        $sReq = '';
        $sReqDebug = '';
        $rArr = array();

	// Loops through the checkoutparams in the hide_billing_details to determine what values are returned, and if there is any issues then a debug message is shown.
        foreach ($checkoutparams as $k=>$v) {
            
            $value =  str_replace("&","and",$v);
            $rArr[$k] =  $value;
            $sReq .= '&'.$k.'='.$value;
            $sReqDebug .= '&'.$k.'=';
            if (in_array($k, $this->_debugReplacePrivateDataKeys)) {
                $sReqDebug .= '***';
            } else  {
                $sReqDebug .= $value;
            }
        }
        return $rArr;
		}
		
	function calculateDeliveryPrices($seller, $order){
		//Calculate shipping price for each cart items
		
		
			$weight_value = 0;
			$price_value = 0;
			$number_value = 0;
			$totalshippingprice = 0;
			$cart_collection = $order->getAllVisibleItems();
			
			foreach($cart_collection as $prod_item){
				$seller_prod_item = Mage::getModel('marketplace/product')->getCollection()
																   		 ->addFilter('mageproductid', $prod_item->getProductId());
			    foreach($seller_prod_item as $item) {
					$prod_seller = $item->getUserid();
					break;
				}
				
				$shipping_collection = Mage::getModel('clarion_seller/shippingprices')->getCollection()
																 ->addFilter('mageuserid', $seller);
				
				
				if($prod_seller == $seller){
					$weight_value += $prod_item->getWeight() * $prod_item->getQty_ordered();
					$price_value += $prod_item->getPrice() * $prod_item->getQty_ordered();
					$number_value += $prod_item->getQty_ordered();
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

	return $totalshippingprice;
	}


	function createCouponDiscount($total, $qty, $merchant){

		$appliedRuleIds = Mage::getSingleton('checkout/session')->getQuote()->getAppliedRuleIds();
		$oRule = Mage::getModel('salesrule/rule')->load($appliedRuleIds);
		$dataRule =$oRule->getData();
		if($dataRule['simple_action'] == 'by_percent' ){
//			print_r($dataRule);
//			print_r($dataRule['use_auto_generation']);
//			print_r($dataRule['simple_action']);
//			print_r($dataRule['discount_amount']);
			$discount= intval($dataRule['discount_amount']);
			$price = ($discount / 100) * $total;
			$couponCode = Mage::getSingleton('checkout/session')
							->getQuote()
							->getCouponCode();
			$discountvariable = "<item><id>". $appliedRuleIds . "</id><model>" . $couponCode . "</model><description>". $dataRule['discount_amount'] ."</description><qty>". $qty ."</qty><price>". "-" . $price ."</price><merchant>". $merchant ."</merchant>";
		}

		return $totalshippingprice;
	}

	function applyCouponDiscount(){

	}
}
