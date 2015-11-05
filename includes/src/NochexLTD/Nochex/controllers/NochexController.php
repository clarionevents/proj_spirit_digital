<?php

/**
 * @title	   Nochex Checkout Module
 * @package    NochexLTD_Nochex
 * @copyright  Copyright (c) 2011 Nochex LTD. (http://www.nochex.com)
 * @author 	   Nochex 
 **/

class NochexLTD_Nochex_NochexController extends Mage_Core_Controller_Front_Action
{
    protected $_order;
	
	public function cancelAction()
                {
                $session = Mage::getSingleton('checkout/session');
                $payment = Mage::getModel('nochex/nochex');
                $order = Mage::getModel('sales/order');
                // gets the recent order that has been cancelled.
                $order->loadByIncrementId($session->getLastRealOrderId());
                $order->addStatusToHistory($payment->getCancelOrderStatus(), 'Customer has cancelled their order.');
                $order->save();
                
                                foreach ($order->getAllVisibleItems() as $item) 
                                {
                                                $product = Mage::getModel('catalog/product')->loadbyAttribute('sku', $item->getSku());
                                                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);

                                                $stockData = $stockItem->getData();

                                                $old_quantity = (int)$stockData['qty'] + (int)$item["qty_ordered"];
                                                                                                
                                                $stockData['qty'] = $old_quantity;
                                                $stockData['is_in_stock'] = 1;
                                                $stockItem->setData($stockData);
                                                $stockItem->save();                       
                                }
				//Goes to the function cancelledAction()
				$this->_redirect('nochex/nochex/cancelled');               
		}
		
	 public function cancelledAction()
    {
	//Creates a message which is to be created when redirected to the checkout/cart page when cancelling an order.
		Mage::getSingleton('core/session')->addNotice("Your Order has been Cancelled!");
		session_write_close(); 
		$this->_redirect('checkout/cart');
    }
	
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $payment = Mage::getModel('nochex/nochex');
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($session->getLastRealOrderId());
        $order->addStatusToHistory($payment->getNewOrderStatus(), 'Customer was Redirected to Nochex.');
        $order->save();
        $this->getResponse()->setBody($this->getLayout()->createBlock('nochex/redirect')->toHtml());
    }
	
	protected function saveInvoice (Mage_Sales_Model_Order $order)
    {
        if ($order->canInvoice()) {
            $invoice = $order->prepareInvoice();
            $invoice->register()->capture();
            Mage::getModel('core/resource_transaction')
               ->addObject($invoice)
               ->addObject($invoice->getOrder())
               ->save();
            return true;
        }

        return false;
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

	
	
	public function apcAction()
    {  
        Mage::log('NOCHEX POST DATA:'.print_r($_REQUEST, true));

        $payment = Mage::getModel('nochex/nochex');
        if (!$payment->getConfigData('active')) {
            die('Nochex is Disabled');
        }
		
		$postvars = http_build_query($_POST);
		
        $url = "http://www.nochex.com/nochex.dll/apc/apc";

		// Curl code to post variables back
		$ch = curl_init(); // Initialise the curl tranfer
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); // Set POST fields
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60); // set connection time out variable - 60 seconds	
		curl_setopt ($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1); 
		$output = curl_exec($ch); // Post back
		curl_close($ch);
		die($output);
		$orderid = $_REQUEST['order_id'];
		$transactionid = $_REQUEST['transaction_id'];
		$transactiondate = $_REQUEST['transaction_date'];
		$toemail = $_REQUEST['to_email'];
		$fromemail = $_REQUEST['from_email'];
		$custom = $_REQUEST['custom'];
		$amount = $_REQUEST['amount'];
		$security_key = $_REQUEST['security_key'];
		$status = $_REQUEST['status'];
		
		
		
			$apcItem = 'APC Details: - Order ID: '.$orderid.', Transaction ID: '.$transactionid.', Transaction Date: '.$transactiondate.', To Email: '.$toemail.', From Email: '.$fromemail.', Custom: '.$custom.', Amount: '.$amount.', Security Key: '.$security_key.', Status: '.$status.'.';
			$this->writeDebug($apcItem);
			
			
		try {
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$query = "INSERT INTO " . Mage::getSingleton('core/resource')->getTableName('nochex') . " (order_id, transaction_id, transaction_date, to_email, from_email, custom, amount, security_key, status, nochex_response) VALUES ('$orderid', '$transactionid', '$transactiondate', '$toemail', '$fromemail', '$custom', '$amount', '$security_key', '$status', '$output')";
			$write->query($query);		
			} catch (Exception $e){
			Mage::log('SQL Error: '.$e->getMessage());
			
			}
			
		$APC2 = 'NOCHEX APC Response: '.$output;
		$this->writeDebug($APC2);
		
        Mage::log('NOCHEX APC Response: '.$output);
		
		if($payment->getConfigData("test_mode")){
		$order = Mage::getModel('sales/order');
        $order = $order->loadByIncrementId(intval($orderid));
		$order->addStatusToHistory($payment->getNewOrderStatus(), 'THIS IS A TEST TRANSACTION. NO MONEY HAS ACTUALLY BEEN RECEIVED', true);
		$order->save();
		}
		
        if ($output == 'AUTHORISED') {
		
            $order = Mage::getModel('sales/order');
            /*$order = $order->loadByIncrementId(intval($orderid));*/
            $order = $order->load($orderid);
			$this->saveInvoice($order);	
			/*$order->addStatusToHistory($payment->getSuccessfulOrderStatus(), 'APC Authorised - Payment Complete', true);*/
			$order->addStatusHistoryComment('APC Authorised - Payment Complete', Mage_Sales_Model_Order::STATE_COMPLETE);
			/*$order->setStatus($payment->getSuccessfulOrderStatus());*/			
            $order->sendNewOrderEmail();
            $order->setEmailSent(true);
            $order->save();
        }else{
			$order = Mage::getModel('sales/order');
			$order = $order->load($orderid);
            /*$order = $order->loadByIncrementId(intval($orderid));*/
			/*$order->addStatusToHistory($payment->getSuccessfulOrderStatus(), 'APC Declined - Payment Complete', true);*/
			$order->addStatusHistoryComment('APC Authorised - Payment Complete', Mage_Sales_Model_Order::STATE_CANCELED);
			/*$order->setStatus($payment->getSuccessfulOrderStatus());*/
            $order->sendNewOrderEmail();
            $order->setEmailSent(true);
            $order->save();
		}
		
		
		$this->writeDebug($APC3);
    }


}