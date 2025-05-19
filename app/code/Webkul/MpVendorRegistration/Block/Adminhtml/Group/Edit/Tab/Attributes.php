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
namespace Webkul\MpVendorRegistration\Block\Adminhtml\Group\Edit\Tab;

use Webkul\MpVendorRegistration\Model\ResourceModel\
    VendorRegistrationAssignGroup\CollectionFactory as VendorRegistrationAssignGroupCollection;
use Webkul\MpVendorRegistration\Model\ResourceModel\
    VendorRegistrationAttribute\CollectionFactory as VendorRegistrationAttributeCollection;

class Attributes extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Block template
     *
     * @var string
     */
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var NewsCollection
     */
    protected $assignCollection;

    protected $resource;

    /**
     * Review action pager
     *
     * @var \Magento\Review\Helper\Action\Pager
     */
    protected $reviewActionPager = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param NewsCollection $newsCollection,
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Review\Helper\Action\Pager $reviewActionPager,
        VendorRegistrationAssignGroupCollection $assignCollection,
        VendorRegistrationAttributeCollection $attributesCollection,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->attributeCollection = $attributesCollection;
        $this->assignCollection = $assignCollection;
        $this->resource = $resource;
        $this->jsonEncoder = $jsonEncoder;
        $this->reviewActionPager = $reviewActionPager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('assign_group_attribute');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'entity_id') {
            $selectedIds = $this->getSelectedValues();
            if (empty($selectedIds)) {
                $selectedIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $selectedIds]);
            } else {
                if ($selectedIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $selectedIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Save search results
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _afterLoadCollection()
    {
        /** @var $actionPager \Magento\Review\Helper\Action\Pager */
        $actionPager = $this->reviewActionPager;
        $actionPager->setStorageId('group');
        return parent::_afterLoadCollection();
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        $connection = $this->resource->getConnection();
        $grpAttribute = $this->assignCollection->create()
                        ->getResource()
                        ->getTable('mp_vendor_registration_assign_group');
        $collection = $this->attributeCollection
                            ->create();
        $collection->getSelect()->join(
            $grpAttribute.' as mpvrg',
            'main_table.entity_id = mpvrg.attribute_id'
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * This function will return the json of the attribute ids
     **/
    public function getSelectedAttrJson()
    {
        $jsonUsers = [];
        $attributes = array_keys($this->getSelectedGroupAttr());
        foreach ($attributes as $key => $value) {
            $jsonUsers[$value] = 0;
        }
        return $this->jsonEncoder->encode((object)$jsonUsers);
    }

    /**
     * @return array|null
     */
    public function getSelectedGroupAttr()
    {
        $attributes = [];
        $groupCollection = $this->assignCollection->create()->addFieldToFilter('group_id', $this->getGroupId());

        foreach ($groupCollection as $model) {
            $attributes[$model->getAttributeId()] = ['position' => $model->getAttributeId()];
        }
        return $attributes;
    }

    /**
     * assign attrbute to group
     * @return array
     **/
    public function getSelectedValues()
    {
        $attrCollection = $this->attributeCollection->create();
        $collection = $this->assignCollection->create()->addFieldToFilter('group_id', ['eq' => $this->getGroupId()]);
        
        $assignedIds = [];
        $attrIds = [];
        foreach ($collection as $model) {
            $attrIds [] = $model->getAttributeId();
        }
        if (count($attrIds)) {
            foreach ($attrCollection as $wkmodel) {
                if (in_array($wkmodel->getId(), $attrIds)) {
                    $assignedIds [] = $wkmodel->getId();
                }
            }
        }
        return $assignedIds;
    }

    /**
     * @return string
     */
    public function getRowUrl($row)
    {
        return "javascript:void(0)";
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('vendorregistration/*/attributesGrid', ['_current' => true]);
    }

    /**
     * @return array|null
     */
    public function getGroupId()
    {
        if ($this->coreRegistry->registry('group_id')) {
            return $this->coreRegistry->registry('group_id');
        } else {
            return $this->getRequest()->getParam('id');
        }
    }
}
