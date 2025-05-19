<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ProductFinder
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form;
use Magento\Framework\Exception\LocalizedException;
use Magento\ImportExport\Model\Import;
use Mageplaza\ProductFinder\Helper\Data as HelperData;

/**
 * Class ImportPromoted
 * @package Mageplaza\ProductFinder\Block\Adminhtml\Rule\Edit\Tab\Renderer
 */
class ImportPromoted extends Generic
{
    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id'      => 'import_promoted_form',
                'class'   => 'mppf_form',
                'action'  => $this->getUrl(
                    '*/import/importPromoted',
                    [
                        'form_key' => $this->getFormKey(),
                        'rule_id'  => $this->getRequest()->getParam('rule_id'),
                        'mode'     => $this->getRequest()->getParam('mode')
                    ]
                ),
                'method'  => 'post',
                'enctype' => 'multipart/form-data',
            ],
        ]);

        $fieldset = $form->addFieldset('base_fieldset', []);

        $fieldset->addField(Import::FIELD_NAME_SOURCE_FILE, 'file', [
            'name'               => Import::FIELD_NAME_SOURCE_FILE,
            'label'              => __('Select File'),
            'title'              => __('Select File'),
            'required'           => true,
            'class'              => 'input-file',
            'after_element_html' => $this->_getDownloadSampleFileHtml()
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    protected function _getDownloadSampleFileHtml()
    {
        $downloadUrl = $this->getUrl(
            'mpproductfinder/import/download/',
            ['filename' => HelperData::SAMPLE_PROMOTED_PRODUCTS_FILE_NAME]
        );

        $html = '<span id="sample-file-span"><a id="sample-file-link" href="' . $downloadUrl . '">'
            . __('Download Sample File') . '</a></span><div class="note admin__field-note" id="import_file-note">'
            . __('Support file type: csv') . '</div><input type="checkbox" value="1" name="delete" id="replace_check">'
            . '<label for="replace_check">' . __('Replace exist promoted products') . '</label>';

        return $html;
    }
}
