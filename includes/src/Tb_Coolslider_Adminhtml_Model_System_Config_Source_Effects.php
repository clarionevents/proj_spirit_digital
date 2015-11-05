<?php
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Tb
 * @package    Tb_Coolslider
 * @copyright  Copyright (c) 2014
 * @license    http://opensource.org/licenses/OSL-3.0
 */


class Tb_Coolslider_Adminhtml_Model_System_Config_Source_Effects{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => '',
                'label'=>Mage::helper('adminhtml')->__('---Please select---')),
            array(
                'value' => 'sliceDown',
                'label'=>Mage::helper('adminhtml')->__('Slice Down')),
            array(
                'value' => 'sliceDownLeft',
                'label'=>Mage::helper('adminhtml')->__('Slice Down Left')),
			array(
                'value' => 'sliceUp',
                'label'=>Mage::helper('adminhtml')->__('Slice Up')),
            array(
                'value' => 'sliceUpLeft',
                'label'=>Mage::helper('adminhtml')->__('Slice Up Left')),
			array(
                'value' => 'sliceUpDown',
                'label'=>Mage::helper('adminhtml')->__('Slice Up Down')),
			array(
                'value' => 'sliceUpDownLeft',
                'label'=>Mage::helper('adminhtml')->__('Slice Up Down Left')),
            array(
                'value' => 'sliceUpDown',
                'label'=>Mage::helper('adminhtml')->__('Slice Up Down')),
            array(
                'value' => 'fold',
                'label'=>Mage::helper('adminhtml')->__('Fold')),
            array(
                'value' => 'fade',
                'label'=>Mage::helper('adminhtml')->__('Fade')),
            array(
                'value' => 'random',
                'label'=>Mage::helper('adminhtml')->__('Random')),
            array(
                'value' => 'slideInRight',
                'label'=>Mage::helper('adminhtml')->__('Slide In Right')),
            array(
                'value' => 'slideInLeft',
                'label'=>Mage::helper('adminhtml')->__('Slide In Left')),
            array(
                'value' => 'boxRandom',
                'label'=>Mage::helper('adminhtml')->__('Box Random')),
            array(
                'value' => 'boxRain',
                'label'=>Mage::helper('adminhtml')->__('Box Rain')),
            array(
                'value' => 'boxRainReverse',
                'label'=>Mage::helper('adminhtml')->__('Box Rain Reverse')),
            array(
                'value' => 'boxRainGrow',
                'label'=>Mage::helper('adminhtml')->__('Box Rain Grow')),
            array(
                'value' => 'boxRainGrowReverse',
                'label'=>Mage::helper('adminhtml')->__('Box Rain Grow Reverse')),
        );
    }

}
?>