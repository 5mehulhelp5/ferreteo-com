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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Attribute\Edit;

use Magento\Store\Model\ResourceModel\Store\Collection;

class Options extends \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options
{
    /**
     * @var string
     */
    protected $_template = 'Webkul_MpVendorRegistration::customfields/options.phtml';

    /**
     * Retrieve attribute object from registry
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     * @codeCoverageIgnore
     */
    protected function getAttributeObject()
    {
        return $this->_registry->registry('entity_attribute');
    }
}
