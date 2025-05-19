<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Attribute\Edit\Tab;

use Webkul\MpVendorRegistration\Block\Adminhtml\Attribute\Edit\AbstractMain;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Main extends AbstractMain
{
    /**
     * Adding product form elements for editing attribute
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeObject */
        $attributeObject = $this->getAttributeObject();
        /* @var $form \Magento\Framework\Data\Form */
        $form = $this->getForm();
        /* @var $fieldset \Magento\Framework\Data\Form\Element\Fieldset */
        $fieldset = $form->getElement('base_fieldset');

        $frontendInputElm = $form->getElement('frontend_input');
        $additionalTypes = [
            ['value' => 'image', 'label' => __('Media Image')],
            ['value' => 'file', 'label' => __('File')],
        ];
        $magentoArr = $frontendInputElm->getValues();
        unset($magentoArr[300]);
        $frontendInputValues = array_merge($magentoArr, $additionalTypes);
        $frontendInputElm->setValues($frontendInputValues);
        return $this;
    }

    /**
     * Retrieve additional element types for product attributes
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return ['apply' => \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Apply::class];
    }
}
