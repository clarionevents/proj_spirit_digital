<?php
/**
 * Tb_Coolslider
 * 
 /****************************************************************************
 *                      MAGENTO EDITION USAGE NOTICE                         *
 ****************************************************************************/
 /* This package designed for Magento Community edition. Author does not provide extension support in case of incorrect edition usage.
 /****************************************************************************
 * @category 	TB
 * @package 	Tb_Coolslider
 * @copyright 	Copyright (c) 2014
 * @license 	http://opensource.org/licenses/OSL-3.0
 */
/**
 */
?>
<?php


class Tb_Coolslider_Block_Adminhtml_Slider_Edit_Tabs 
	  extends Mage_Adminhtml_Block_Widget_Tabs{
    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('coolslider')->__('Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('info', array(
            'label'     => Mage::helper('coolslider')->__('Slide Information'),
            'content'   => $this->getLayout()->createBlock('coolslider/adminhtml_slider_edit_tab_info')->initForm()->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
