<?php
class Clarion_Order_Model_Checkout_Observer {
    	
		
    public function sendNewOrderEmail(Varien_Event_Observer $observer){
    	
		//Set new order status
		$orderIds = $observer->getData('order_ids');
		$order = Mage::getModel('sales/order')->load($orderIds[0]);
		$order->addStatusHistoryComment("Order has successfull been payed on the nochex side", Mage_Sales_Model_Order::STATE_COMPLETE);
		$order->save();
    	
		//Send new order email to the customer
		$order->sendNewOrderEmail();
    	
    }


}