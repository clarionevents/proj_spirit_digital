<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class Clarion_Webkul_ClarioneaccountController extends Mage_Customer_AccountController{
	public function askquestiontosellerAction(){
		$customerid=Mage::getSingleton('customer/session')->getCustomerId();
		$seller = Mage::getModel('customer/customer')->load($customerid);
		$email = $seller->getEmail();
		$name = $seller->getFirstname()." ".$seller->getLastname();
		$admin = Mage::getModel('admin/user')->load(1)->getEmail();
		$emailTemp = Mage::getModel('core/email_template')->loadDefault('queryadminemail');
		$emailTempVariables = array();
		$emailTempVariables['myvar1'] = $_POST['subject'];
		$emailTempVariables['myvar2'] =$name;
		$emailTempVariables['myvar3'] = $_POST['ask'];
		$processedTemplate = $emailTemp->getProcessedTemplate($emailTempVariables);
		$emailTemp->setSenderName($name);
		$emailTemp->setSenderEmail($email);
		// $emailTemp->send($_POST['selleremail'],'Administrators',$emailTempVariables);
		$emailTemp->send($name,'Administrators',$emailTempVariables);
	}
}

?>