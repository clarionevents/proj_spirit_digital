<?php
class Webkul_Mppercountryperproductshipping_Model_Carrier_LocalDelivery extends Mage_Shipping_Model_Carrier_Abstract
{
    /* Use group alias */
    protected $_code = 'mppercountryperproductshipping';
    public function collectRates(Mage_Shipping_Model_Rate_Request $request){
        //skip if not enabled
        if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')||Mage::getStoreConfig('carriers/mp_multi_shipping/mp_multi_shipping_active')){
           return false;
		}
		
        $result = Mage::getModel('shipping/rate_result');
		$session = Mage::getSingleton('checkout/session');
		$postcode=$session->getQuote()->getShippingAddress()->getPostcode();
		$countrycode=$session->getQuote()->getShippingAddress()->getCountry();
		$postcode=str_replace('-', '', $postcode);
		$shippingdetail=array();
		$shippostaldetail=array('countrycode'=>$countrycode,'postalcode'=>$postcode);
		/****/
		foreach($session->getQuote()->getAllVisibleItems() as $item) {
			$proid=$item->getProductId();
			$collection=Mage::getModel('marketplace/product')
				->getCollection()->addFieldToFilter('mageproductid',array('eq'=>$proid));
			foreach($collection as $temp){
				$partner=$temp->getUserid();
			}
			
			$product=Mage::getModel('catalog/product')->load($proid)->getWeight();
			$weight=$product*$item->getQty();
			if(count($shippingdetail)==0){
				array_push($shippingdetail,array('seller_id'=>$partner,'items_weight'=>$weight,'product_name'=>$item->getName(),'qty'=>$item->getQty(),'item_id'=>$item->getId()));
			}else{
				$shipinfoflag=true;
				$index=0;
				foreach($shippingdetail as $itemship){
					if($itemship['seller_id']==$partner){
						$itemship['items_weight']=$itemship['items_weight']+$weight;
						$itemship['product_name']=$itemship['product_name'].",".$item->getName();
						$itemship['item_id']=$itemship['item_id'].",".$item->getId();
						$itemship['qty']=$itemship['qty']+$item->getQty();
						$shippingdetail[$index]=$itemship;
						$shipinfoflag=false;
					}
					$index++;
				}
				if($shipinfoflag==true){
					array_push($shippingdetail,array('seller_id'=>$partner,'items_weight'=>$weight,'product_name'=>$item->getName(),'qty'=>$item->getQty(),'item_id'=>$item->getId()));
				}
			}
		}
		$shippingpricedetail=$this->getShippingPricedetail($shippingdetail,$shippostaldetail);
	
		if($shippingpricedetail['errormsg']!==""){
			Mage::getSingleton('core/session')->setShippingCustomError($shippingpricedetail['errormsg']);
			return $result;
		}
		/*store shipping in session*/
		$shippingAll=Mage::getSingleton('core/session')->getData('shippinginfo');
		$shippingAll[$this->_code]=$shippingpricedetail['shippinginfo'];
		Mage::getSingleton('core/session')->setData('shippinginfo',$shippingAll);
		
		$method = Mage::getModel('shipping/rate_result_method');
		$method->setCarrier($this->_code);
		$method->setCarrierTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/title'));
        /* Use method name */
		$method->setMethod($this->_code);
	    $method->setMethodTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/name'));
		$method->setCost($shippingpricedetail['handlingfee']);
		$method->setPrice($shippingpricedetail['handlingfee']); 
        $result->append($method);
        return $result;	
    }
    
     public function getShippingPricedetail($shippingdetail,$shippostaldetail){
		$shippinginfo=array();
		$handling=0;
		$session = Mage::getSingleton('checkout/session');
		foreach($shippingdetail as $shipdetail){
			$price=0;
			$itemsarray=explode(',',$shipdetail['item_id']);
			foreach($session->getQuote()->getAllItems() as $item) {
				$newprice = 0;
				if(in_array($item->getId(),$itemsarray)){
					$mpshippingcharge=Mage::getModel('catalog/product')->load($item->getProductId())->getMpShippingCountryCharge();
					$totalcounries = explode('/',$mpshippingcharge);
					foreach($totalcounries as $pric){
					  	$sepratecountry= explode(',',$pric); 
                      	if (in_array($shippostaldetail['countrycode'], $sepratecountry)) {
                           $newprice = $sepratecountry[1];
                      	}
				    }
					if(floatval($newprice)==0){
						$price=$price+floatval($this->getConfigData('defalt_ship_amount'));
					} else {
						// $price=$price+($newprice*$item->getQty());
						$price=$price+$newprice;
					}
				}
			}
				
			$handling= $handling+$price;
			$submethod=array(array('method'=>Mage::getStoreConfig('carriers/'.$this->_code.'/title'),'cost'=>$price,'error'=>0));
			array_push($shippinginfo,array('seller_id'=>$shipdetail['seller_id'],'methodcode'=>$this->_code,'shipping_ammount'=>$price,'product_name'=>$shipdetail['product_name'],'submethod'=>$submethod,'item_ids'=>$shipdetail['item_id']));
		}
		$msg="";
		return array('handlingfee'=>$handling,'shippinginfo'=>$shippinginfo,'errormsg'=>$msg);
	}
}
 
