<?php
class Varien_Data_Form_Element_Thumbnail extends Varien_Data_Form_Element_Abstract
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    public function getElementHtml()
    {
        $html = '';

        if ($this->getValue()) {
             $url = $this->_getUrl();

            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::getBaseUrl('skin').'frontend/default/default/images/slider_image/'.$url;
			   
            }

            $html = '<a href="' . $url . '"'
                . ' onclick="imagePreview(\'' . $this->getHtmlId() . '_image\'); return false;">'
                . '<img src="' . $url . '" id="' . $this->getHtmlId() . '_image" title="' . $this->getValue() . '"'
                . ' alt="' . $this->getValue() . '" height="130" width="278" class="small-image-preview v-middle" />'
                . '</a> ';
        }
        $this->setClass('input-file');
        $html.= parent::getElementHtml();

        return $html;
    }

    protected function _getUrl()
    {
        return $this->getValue();
    }

    public function getName()
    {
        return  $this->getData('name');
    }
}
