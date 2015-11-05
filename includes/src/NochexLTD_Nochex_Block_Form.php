<?php

/**
 * @title	   Nochex Checkout Module
 * @package    NochexLTD_Nochex
 * @copyright  Copyright (c) 2011 Nochex LTD. (http://www.nochex.com)
 * @author 	   Nochex 
 **/

class NochexLTD_Nochex_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {        
		parent::_construct();
		
        $this->setTemplate('nochexltd/nochex/form.phtml');
		
    }
}