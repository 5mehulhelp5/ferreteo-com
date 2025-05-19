<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;
use Magento\Customer\Model\AttributeMetadataDataProviderFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;
    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory
     */
    protected $customFieldFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;
    /**
     * @var \Magento\Customer\Model\AttributeFactory
     */
    protected $attributeFactory;
    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetaData;

    /**
     * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     */
    protected $groupAssignFactory;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $typeList;

    /**
     * @var \Magento\PageCache\Model\Config
     */
    protected $config;

    /**
     * Undocumented function
     *
     * @param Action\Context $context
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param \Magento\Customer\Model\AttributeFactory $attributeFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $customfieldsFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $groupAssignFactory
     * @param AttributeMetadataDataProviderFactory $attributeMetaData
     * @param \Magento\PageCache\Model\Config $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $typeList
     * @param Config $eavConfig
     */
    public function __construct(
        Action\Context $context,
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Customer\Model\AttributeFactory $attributeFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $customfieldsFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $groupAssignFactory,
        AttributeMetadataDataProviderFactory $attributeMetaData,
        \Magento\PageCache\Model\Config $config,
        \Magento\Framework\App\Cache\TypeListInterface $typeList,
        Config $eavConfig
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->customFieldFactory = $customfieldsFactory;
        $this->attributeFactory = $attributeFactory;
        $this->attributeMetaData = $attributeMetaData;
        $this->groupAssignFactory = $groupAssignFactory;
        $this->eavConfig = $eavConfig;
        $this->config = $config;
        $this->typeList = $typeList;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorRegistration::index');
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);
        if ($this->config->isEnabled()) {
            $this->typeList->invalidate(
                \Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER
            );
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $attributeCode = $this->getRequest()->getParam('attribute_code');

            $customerEntity = $this->eavConfig->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customFieldModel = $this->customFieldFactory->create();
            $groupAssignModel = $this->groupAssignFactory->create();

            $attributeId = $this->getRequest()->getParam('attribute_id');
            $id = '';
            if (!$attributeId) {

                /** validate the attribute code length and pre-existence **/
                if (!$this->validateAttributeCode($attributeCode)) {
                    return $resultRedirect->setPath('vendorregistration/attribute/');
                }

                $attributeCode = "wkmpvr_" . $attributeCode;
                $attribute = $this->eavConfig->getAttribute('customer', $attributeCode);

                $attribute->addData(
                    $this->getDefaultEntities(
                        $data['frontend_input'],
                        $attributeSetId,
                        $attributeGroupId
                    )
                );
                try {
                    $attribute->save();
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                }
                /** save record for webkul attribute manager table **/
                $customFieldModel->setAttributeId($attribute->getId());
                $customFieldModel->setIsRequired((int) $data['is_required']);
                $customFieldModel->setAttributeStatus(1);
                $customFieldModel->setShowInFront(1);
                $customFieldModel->setAttributeCode($attributeCode);
                $customFieldModel->setAttributeLabel($data['frontend_label'][0]);
                $customFieldModel->setSortOrder($data['sort_order']);
                $customFieldModel->save();

                $vendorAttributeId = $customFieldModel->getId();
                /* Assign Attribute to vendor group */
                $this->assignVendorGroup(
                    $groupAssignModel,
                    $data,
                    $vendorAttributeId
                );
                $this->messageManager->addSuccess(__('You saved the vendor attribute.'));
            } else {
                $model = $this->attributeFactory->create();
                $model->load($attributeId);
                if (!($model->getAttributeId())) {
                    $this->messageManager->addError(__('This attribute no longer exists.'));
                    return $resultRedirect->setPath('vendorregistration/attribute/');
                } else {
                    $data['attribute_code'] = $model->getAttributeCode();
                    $data['frontend_input'] = $model->getFrontendInput();

                    $collection = $customFieldModel->getCollection()
                        ->addFieldToFilter('attribute_id', $attributeId);
                    foreach ($collection as $value) {
                        $id = $value->getEntityId();
                    }
                    $customFieldModel->load($id);

                    if (!isset($data['frontend_class'])) {
                        $data['frontend_class'] = "";
                    }

                    /* if attribute is required */
                    if (isset($data['is_required']) && ($data['is_required'] == 1)) {
                        $data['frontend_class'] .= ' required';
                    }
                    $customData['is_required'] = $data['is_required'];
                    if ($model->getIsUserDefined() == 1) {
                        $data['is_required'] = 0;
                        $model->addData($data);
                        try {
                            $model->save();
                        } catch (\Exception $e) {
                            $this->messageManager->addError($e->getMessage());
                        } catch (\Magento\Framework\Exception\LocalizedException $e) {
                            $this->messageManager->addError($e->getMessage());
                        }
                    }

                    $customFieldModel->setIsRequired((int) $customData['is_required']);
                    $customFieldModel->setAttributeLabel($data['frontend_label'][0]);
                    $customFieldModel->setSortOrder($data['sort_order']);
                    try {
                        $customFieldModel->save();
                    } catch (\Exception $e) {
                        $this->messageManager->addError($e->getMessage());
                    } catch (\Magento\Framework\Exception\LocalizedException $e) {
                        $this->messageManager->addError($e->getMessage());
                    }
                    $vendorAttributeId = $customFieldModel->getEntityId();

                    /*
                    Assign Attribute to vendor group
                     */
                    $this->assignVendorGroup($groupAssignModel, $data, $vendorAttributeId);
                }
            }
            if ($redirectBack) {
                $this->_getSession()->setFormData($data);
                return $resultRedirect->setPath(
                    'vendorregistration/attribute/edit',
                    ['id' => $vendorAttributeId, 'attribute_code' => $attributeCode, '_current' => true]
                );
            }
        }
        return $resultRedirect->setPath('vendorregistration/attribute/');
    }
    /**
     * Undocumented function
     *
     * @param [type] $attributeCode
     * @return ture|false
     */
    public function validateAttributeCode($attributeCode)
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $validatorAttrCode = new \Zend_Validate_Regex(['pattern' => '/^[a-z][a-z_0-9]{0,25}$/']);

        if (strlen($attributeCode) < 0 || !$validatorAttrCode->isValid($attributeCode)) {
            $this->messageManager->addError(
                __(
                    'Attribute name "%1" is invalid.' .
                    ' Please use only letters (a-z), numbers (0-9) or underscore(_) in this field,' . '
                     first character should be a letter.',
                    $attributeCode
                )
            );
            return false;
        }

        // check for attribute code pre-exists or not.
        $attribute = $this->eavConfig->getAttribute('customer', 'wkmpvr_' . $attributeCode);
        $collection = $attribute->getCollection()
            ->addFieldToFilter('attribute_code', 'wkmpvr_' . $attributeCode);
        if ($collection->getSize() > 0) {
            $this->messageManager->addError(__('The attribute ID already exists.'));
            return false;
        }

        return true;
    }
    /**
     * Retrieve default entities: customer custom attribute.
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities($field, $attributeSetId, $attributeGroupId)
    {
        $data = $this->getRequest()->getPostValue();

        if (!(isset($data['frontend_class']))) {
            $data['frontend_class'] = "";
        }

        if (isset($data['is_required']) && ($data['is_required'] == 1)) {
            $data['frontend_class'] .= " required";
        }

        $entities = [
            'frontend_input' => $field,
            'is_system' => false,
            'is_user_defined' => true,
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            // 'used_in_forms'         => ['adminhtml_customer', 'customer_account_edit', 'customer_account_create'],
            'frontend_label' => $data['frontend_label'],
            'frontend_class' => $data['frontend_class'],
            'sort_order' => $data['sort_order'],
            'position' => $data['sort_order'],
            'is_visible' => false,
        ];

        switch ($field) {
            case "date":
                $entities['frontend_type'] = 'datetime';
                $entities['backend_type'] = 'datetime';
                $entities['frontend_model'] = \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class;
                $entities['backend_model'] = \Magento\Eav\Model\Entity\Attribute\Backend\Datetime::class;
                $entities['validate_rules'] = '{"input_validation":"date"}';
                break;
            case "select":
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                $entities['source_model'] = \Magento\Eav\Model\Entity\Attribute\Source\Table::class;
                $entities['option'] = $data['option'];
                break;
            case "multiselect":
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                $entities['backend_model'] = \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class;
                $entities['source_model'] = \Magento\Eav\Model\Entity\Attribute\Source\Table::class;
                $entities['option'] = $data['option'];
                break;
            case "boolean":
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                break;
            case "image":
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                $entities['backend_model'] = \Magento\Eav\Model\Entity\Attribute\Backend\DefaultBackend::class;
                break;
            case "file":
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                $entities['backend_model'] = \Magento\Eav\Model\Entity\Attribute\Backend\Increment::class;
                break;
            default: // for text and textarea
                $entities['frontend_type'] = 'varchar';
                $entities['backend_type'] = 'varchar';
                break;
        }
        return $entities;
    }

    /**
     * @param  \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroup $model
     * @param  array                                                    $data
     * @param  int                                                      $attributeId
     */
    public function assignVendorGroup(
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroup $model,
        $data,
        $vendorAttributeId
    ) {
        if (isset($data['assign_group']) && count([$data['assign_group']])) {
            $groups = $model->getCollection()
                ->addFieldToFilter('attribute_id', ['eq' => $vendorAttributeId])
                ->addFieldToSelect('group_id')
                ->getColumnValues('group_id');
            $diffrence = array_diff($groups, [$data['assign_group']]);
            try {
                foreach ([$data['assign_group']] as $groupId) {
                    $assignCollection = $model->getCollection()
                        ->addFieldToFilter('attribute_id', ['eq' => $vendorAttributeId])
                        ->addFieldToFilter('group_id', ['eq' => $groupId]);
                    if (!$assignCollection->getSize()) {
                        $model->setAttributeId($vendorAttributeId);
                        $model->setGroupId($groupId);
                        $model->save();
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            }
            foreach ($diffrence as $groupId) {
                $assignCollection = $model->getCollection()
                    ->addFieldToFilter('attribute_id', ['eq' => $vendorAttributeId])
                    ->addFieldToFilter('group_id', ['eq' => $groupId]);
                foreach ($assignCollection as $value) {
                    $value->delete();
                }
            }
        }
    }
}
