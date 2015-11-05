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

class Tb_Coolslider_Block_Adminhtml_Slider_Edit_Tab_Info 
	  					extends Mage_Adminhtml_Block_Widget_Form{

	
    public function initForm()
    {
        $form = new Varien_Data_Form();
		$this->setForm($form);

        $fieldset = $form->addFieldset('slide_form', array('legend'=>Mage::helper('coolslider')->__('Slide information')));
		
		$fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('coolslider')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
        ));

        $fieldset->addField('status', 'select', array(
        'label'     => Mage::helper('coolslider')->__('Status'),
        'name'      => 'status',
        'values'    => array(
          array(
              'value'     => 1,
              'label'     => Mage::helper('coolslider')->__('Enabled'),
          ),

          array(
              'value'     => 0,
              'label'     => Mage::helper('coolslider')->__('Disabled'),
          ),
        ),
        ));
		
		 /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('store_id', 'multiselect', array(
                    'name'      => 'stores[]',
                    'label'     => Mage::helper('coolslider')->__('Store View'),
                    'title'     => Mage::helper('coolslider')->__('Store View'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        $fieldset->addField('url', 'text', array(
          'label'     => Mage::helper('coolslider')->__('Url'),
          'title'     => Mage::helper('coolslider')->__('Url'),
          'required'  => false,
          'name'      => 'url',
          'after_element_html' => '<div class="hint"><p class="note">'.$this->__('e.g. http://magentocommerce.com/products.html').'</p></div>',
        ));
		
		//
        $fieldset->addField('image', 'image', array(
            'label'     => Mage::helper('coolslider')->__('Slide image'),
			'title'     => Mage::helper('coolslider')->__('Slide image'),
            'required'  => false,
            'name'      => 'image',
			'note' => '(*.jpg, *jpeg, *.png, *.gif)'
        ));

		$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('add_variables' => false, 'add_widgets' => false,'files_browser_window_url'=>$this->getBaseUrl().'admin/cms_wysiwyg_images/index/'));
		
		$fieldset->addField('contents', 'editor', array(
		  'name'      => 'contents',
		  'label'     => Mage::helper('coolslider')->__('Content'),
		  'title'     => Mage::helper('coolslider')->__('Content'),
		  'style'     => 'width:430px; height:500px;',
		   'config'      => $wysiwygConfig,
		  'wysiwyg'   => true,
		));
		
		if ( Mage::getSingleton('adminhtml/session')->getSupersliderData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getSupersliderData();
          Mage::getSingleton('adminhtml/session')->setSupersliderData(null);
      } elseif ( Mage::registry('coolslider_data') ) {
          $data = Mage::registry('coolslider_data')->getData();
      }
      

       
		$form->setValues($data);
		if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'hidden', array(
                            'name'      => 'stores[]',
                            'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
		

        
        $this->setForm($form);
        return $this;
    }
}
