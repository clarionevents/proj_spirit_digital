<?php
class Clarion_Seller_Model_Resource_Seller extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('clarion_seller/shippingprices', 'index_id');
    }
}