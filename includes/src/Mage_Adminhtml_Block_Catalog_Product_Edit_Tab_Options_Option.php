<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * customers defined options
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */

class Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option extends Mage_Adminhtml_Block_Widget
{
    protected $_product;

    protected $_productInstance;

    protected $_values;

    protected $_itemCount = 1;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product/edit/options/option.phtml');
        $this->setCanReadPrice(true);
        $this->setCanEditPrice(true);
    }

    public function getItemCount()
    {
        return $this->_itemCount;
    }

    public function setItemCount($itemCount)
    {
        $this->_itemCount = max($this->_itemCount, $itemCount);
        return $this;
    }

    /**
     * Get Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->_productInstance) {
            if ($product = Mage::registry('product')) {
                $this->_productInstance = $product;
            } else {
                $this->_productInstance = Mage::getSingleton('catalog/product');
            }
        }

        return $this->_productInstance;
    }

    public function setProduct($product)
    {
        $this->_productInstance = $product;
        return $this;
    }

    /**
     * Retrieve options field name prefix
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'product[options]';
    }

    /**
     * Retrieve options field id prefix
     *
     * @return string
     */
    public function getFieldId()
    {
        return 'product_option';
    }

    /**
     * Check block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
         return $this->getProduct()->getOptionsReadonly();
    }

    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Option'),
                    'class' => 'delete delete-product-option '
                ))
        );

        $path = 'global/catalog/product/options/custom/groups';

        foreach (Mage::getConfig()->getNode($path)->children() as $group) {
            $this->setChild($group->getName() . '_option_type',
                $this->getLayout()->createBlock(
                    (string) Mage::getConfig()->getNode($path . '/' . $group->getName() . '/render')
                )
            );
        }

        return parent::_prepareLayout();
    }

    public function getAddButtonId()
    {
        $buttonId = $this->getLayout()
                ->getBlock('admin.product.options')
                ->getChild('add_button')->getId();
        return $buttonId;
    }

    public function g                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        $value['optionValues'][$i] = array(
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $_value->getOptionTypeId(),
                            'title' => $this->escapeHtml($_value->getTitle()),
                            'price' => ($showPrice)
                                ? $this->getPriceValue($_value->getPrice(), $_value->getPriceType()) : '',
                            'price_type' => ($showPrice) ? $_value->getPriceType() : 0,
                            'sku' => $this->escapeHtml($_value->getSku()),
                            'sort_order' => $_value->getSortOrder(),
                        );

                        if ($this->getProduct()->getStoreId() != '0') {
                            $value['optionValues'][$i]['checkboxScopeTitle'] = $this->getCheckboxScopeHtml(
                                $_value->getOptionId(), 'title', is_null($_value->getStoreTitle()),
                                $_value->getOptionTypeId());
                            $value['optionValues'][$i]['scopeTitleDisabled'] = is_null($_value->getStoreTitle())
                                ? 'disabled' : null;
                            if ($scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                                $value['optionValues'][$i]['checkboxScopePrice'] = $this->getCheckboxScopeHtml(
                                    $_value->getOptionId(), 'price', is_null($_value->getstorePrice()),
                                    $_value->getOptionTypeId());
                                $value['optionValues'][$i]['scopePriceDisabled'] = is_null($_value->getStorePrice())
                                    ? 'disabled' : null;
                            }
                        }
                        $i++;
                    }
                } else {
                    $value['price'] = ($showPrice)
                        ? $this->getPriceValue($option->getPrice(), $option->getPriceType()) : '';
                    $value['price_type'] = $option->getPriceType();
                    $value['sku'] = $this->escapeHtml($option->getSku());
                    $value['max_characters'] = $option->getMaxCharacters();
                    $value['file_extension'] = $option->getFileExtension();
                    $value['image_size_x'] = $option->getImageSizeX();
                    $value['image_size_y'] = $option->getImageSizeY();
                    if ($this->getProduct()->getStoreId() != '0' &&
                        $scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                        $value['checkboxScopePrice'] = $this->getCheckboxScopeHtml($option->getOptionId(),
                            'price', is_null($option->getStorePrice()));
                        $value['scopePriceDisabled'] = is_null($option->getStorePrice())?'disabled':null;
                    }
                }
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }

        return $this->_values;
    }

    /**
     * Retrieve html of scope checkbox
     *
     * @param string $id
     * @param string $name
     * @param boolean $checked
     * @param string $select_id
     * @return string
     */
    public function getCheckboxScopeHtml($id, $name, $checked=true, $select_id='-1')
    {
        $checkedHtml = '';
        if ($checked) {
            $checkedHtml = ' checked="checked"';
        }
        $selectNameHtml = '';
        $selectIdHtml = '';
        if ($select_id != '-1') {
            $selectNameHtml = '[values][' . $select_id . ']';
            $selectIdHtml = 'select_' . $select_id . '_';
        }
        $checkbox = '<input type="checkbox" id="' . $this->getFieldId() . '_' . $id . '_' .
            $selectIdHtml . $name . '_use_default" class="product-option-scope-checkbox" name="' .
            $this->getFieldName() . '['.$id.']' . $selectNameHtml . '[scope][' . $name . ']" value="1" ' .
            $checkedHtml . '/>';
        $checkbox .= '<label class="normal" for="' . $this->getFieldId() . '_' . $id . '_' .
            $selectIdHtml . $name . '_use_default">' . $this->__('Use Default Value') . '</label>';
        return $checkbox;
    }

    public function getPriceValue($value, $type)
    {
        if ($type == 'percent') {
            return number_format($value, 2, null, '');
        } elseif ($type == 'fixed') {
            return number_format($value, 2, null, '');
        }
    }
}
