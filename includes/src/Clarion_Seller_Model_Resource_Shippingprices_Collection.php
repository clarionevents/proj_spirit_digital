<?php
class Clarion_Seller_Model_Resource_Shippingprices_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('clarion_seller/shippingprices');
    }
}