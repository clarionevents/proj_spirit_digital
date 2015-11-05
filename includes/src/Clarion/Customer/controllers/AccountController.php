<?php
require_once Mage::getModuleDir('controllers', 'Mage_Customer').DS.'AccountController.php';
//we need to add this one since Magento wont recognize it automatically
 
class Clarion_Customer_AccountController extends Mage_Customer_AccountController{
	
	/**
     * Change customer password action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard/hello')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }
	
}