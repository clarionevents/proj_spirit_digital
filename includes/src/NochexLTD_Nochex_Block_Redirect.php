<?php

/**
 * @title	   Nochex Checkout Module
 * @package    NochexLTD_Nochex
 * @copyright  Copyright (c) 2011 Nochex LTD. (http://www.nochex.com)
 * @author 	   Nochex 
 **/

class NochexLTD_Nochex_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $nochex = Mage::getModel('nochex/nochex');

        $form = new Varien_Data_Form();
        $form->setAction($nochex->getNochexUrl())
            ->setId('nochex_checkout')
            ->setName('nochex_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($nochex->getNochexCheckoutFormFields() as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('<br /><br /><br /><br /><br /><br /><br /><br /><p align="center">You will be redirected to Nochex in a few seconds.');
		$html.= $this->__('<br /><img src="https://ssl.nochex.com/images/magento/nochex-loader.gif" alt="Progress"/></p>');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("nochex_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }
	
	
}