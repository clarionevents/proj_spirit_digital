<?php
class Tb_Coolslider_Adminhtml_Model_System_Config_Source_Positions{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $content_pos = array(
            array('value' => 'center_left',
                'label'=>Mage::helper('coolslider')->__('Center left')),
            array('value' => 'top_left',
                'label'=>Mage::helper('coolslider')->__('Top left')),
            array('value' => 'top_right',
                'label'=>Mage::helper('coolslider')->__('Top Right')),
            array('value' => 'bottom_left',
                'label'=>Mage::helper('coolslider')->__('Bottom left')),
            array('value' => 'bottom_right',
                'label'=>Mage::helper('coolslider')->__('Bottom right')),
            array('value' => 'center_right',
                'label'=>Mage::helper('coolslider')->__('Center right')),
        );
        return $content_pos;
    }

}
?>