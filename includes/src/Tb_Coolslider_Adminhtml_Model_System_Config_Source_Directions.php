<?php
class Tb_Carouselslide_Adminhtml_Model_System_Config_Source_Directions{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'left', 'label'=>Mage::helper('carouselslide')->__('Right to left')),
            array('value' => 'right', 'label'=>Mage::helper('carouselslide')->__('Left to right')),
        );
    }

}
?>