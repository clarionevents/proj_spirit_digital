<?php

/**
 * @title	   Nochex Checkout Module
 * @package    NochexLTD_Nochex
 * @copyright  Copyright (c) 2011 Nochex LTD. (http://www.nochex.com)
 * @author 	   Nochex 
 **/

class NochexLTD_Nochex_Model_Nochex extends Mage_Payment_Model_Method_Abstract
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
		
		//Stores a session.
		$session = Mage::getSingleton('checkout/session');
		
		
		
		 if($this->getConfigData("xmlCollection") == 1){
		 
		$xmlData = "<items>";
		 
		 foreach ($order->getAllVisibleItems() as $item) {
		 
		  $xmlData .= "<item><id>".$item->getId()."</id><name>".$item->getName()."</name><description>". $item->getName() ."". $item->getDescription()."</description><quantity>".number_format($item->getQtyOrdered(),0)."</quantity><price>".money_format('%.2n', $item->getPrice())."</price></item>";
		 
		 }
		 
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
            'merchant_id' 		=> $this->getConfigData("merchant_id"),
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
			'xml_item_collection' => $xmlData,			
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
		
		
	
}
