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
namespace Webkul\MpVendorRegistration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Module\Setup\Migration;

class InstallDataPatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;
/**
 * Post factory
 *
 * @var \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory
 */
    protected $vendorRegistrationGroupFactory;
    /**
     *
     */
    protected $vendorRegistrationAttributeFactory;
    /**
     *
     */
    protected $customerAttribute;
     /**
      *
      */
    protected $eavCollection;
     /**
      *
      */
    protected $vendorRegistrationAssignGroupFactory;

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorRegistrationGroupFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorRegistrationAttributeFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     * $vendorRegistrationAssignGroupFactory
     * @param \Magento\Customer\Model\ResourceModel\Form\Attribute $customerAttribute
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection $eavCollection
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorRegistrationGroupFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorRegistrationAttributeFactory,
        \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory $vendorRegistrationAssignGroupFactory,
        \Magento\Customer\Model\ResourceModel\Form\Attribute $customerAttribute,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection $eavCollection,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->vendorRegistrationGroupFactory = $vendorRegistrationGroupFactory;
        $this->vendorRegistrationAttributeFactory = $vendorRegistrationAttributeFactory;
        $this->vendorRegistrationAssignGroupFactory = $vendorRegistrationAssignGroupFactory;
        $this->customerAttribute = $customerAttribute;
        $this->eavCollection = $eavCollection;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->installData($this->moduleDataSetup);
    }

    /**
     * Copy Banner and Icon Images to Media
     */
    public function installData($setup)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        $data = [
            [
                'group_name' => 'Personal Info',
                'sort_order' => '1',
                'group_status' => '1',
                'group_by_admin' => '1',
                'show_in_frontend' => '1',
                'group_code' => 'personalinfo'
            ],
            [
                'group_name' => 'Account Info',
                'sort_order' => '2',
                'group_status' => '1',
                'group_by_admin' => '1',
                'show_in_frontend' => '1',
                'group_code' => 'accountinfo'
            ],
            [
                'group_name' => 'Shop Info',
                'sort_order' => '3',
                'group_status' => '1',
                'group_by_admin' => '1',
                'show_in_frontend' => '1',
                'group_code' => 'shopinfo'
            ],
            [
                'group_name' => 'Address Info',
                'sort_order' => '4',
                'group_status' => '1',
                'group_by_admin' => '1',
                'show_in_frontend' => '0',
                'group_code' => 'addressinfo'
            ],
            [
                'group_name' => 'Payment Info',
                'sort_order' => '5',
                'group_status' => '1',
                'group_by_admin' => '1',
                'show_in_frontend' => '1',
                'group_code' => 'paymentinfo'
            ]
        ];
        
        foreach ($data as $vendorGroup) {
            $this->vendorRegistrationGroupFactory->create()->setData($vendorGroup)->save();
        }

        $customerAttrs = $this->customerAttribute->getFormAttributeIds('customer_account_create');
        
        $eavData = $this->eavCollection->addFieldToFilter(
            'attribute_id',
            ['in' => implode(',', $customerAttrs)]
        );
        foreach ($eavData as $data) {
            $vendorAttribute = [
                'attribute_code' => $data->getAttributeCode(),
                'attribute_label' => $data->getFrontendLabel(),
                'attribute_id' => $data->getAttributeId(),
                'is_required' => $data->getIsRequired(),
                'attribute_status' => 1,
                'attribute_by_admin' => 1
            ];
            $this->vendorRegistrationAttributeFactory->create()->setData($vendorAttribute)->save();
        }
        $vendorAttributeprofileurl = [
            'attribute_code' => 'profileurl',
            'attribute_label' => 'Shop Url',
            'attribute_id' => '',
            'is_required' => 1,
            'attribute_status' => 1,
            'attribute_by_admin' => 1
        ];
        $this->vendorRegistrationAttributeFactory->create()->setData($vendorAttributeprofileurl)->save();
        $vendorAttributepayment_source = [
            'attribute_code' => 'payment_source',
            'attribute_label' => 'Payment Details',
            'attribute_id' => '',
            'is_required' => 1,
            'attribute_status' => 1,
            'attribute_by_admin' => 1
        ];
        $this->vendorRegistrationAttributeFactory->create()->setData($vendorAttributepayment_source)->save();

        $groupCollection = $this->vendorRegistrationGroupFactory->create()->getCollection();
        $attributeCollection = $this->vendorRegistrationAttributeFactory->create()->getCollection();
        
        foreach ($attributeCollection as $attributeData) {
            $assignData = [];
            
            foreach ($groupCollection as $groupData) {
                if (strpos($attributeData->getAttributeCode(), 'email') === false &&
                    strpos($groupData->getGroupCode(), 'personal') !== false &&
                    $attributeData->getAttributeId() != 0
                ) {
                    $assignData = [
                        'group_id' => $groupData->getId(),
                        'attribute_id' => $attributeData->getId()
                    ];
                    $this->vendorRegistrationAssignGroupFactory->create()->setData($assignData)->save();
                } elseif (strpos($attributeData->getAttributeCode(), 'email') !== false &&
                        strpos($groupData->getGroupCode(), 'account') !== false
                    ) {
                    $assignData = [
                        'group_id' => $groupData->getId(),
                        'attribute_id' => $attributeData->getId()
                    ];
                    $this->vendorRegistrationAssignGroupFactory->create()->setData($assignData)->save();
                } elseif (strpos($attributeData->getAttributeCode(), 'profileurl') !== false &&
                        strpos($groupData->getGroupCode(), 'shop') !== false
                    ) {
                    $assignData = [
                        'group_id' => $groupData->getId(),
                        'attribute_id' => $attributeData->getId()
                    ];
                    $this->vendorRegistrationAssignGroupFactory->create()->setData($assignData)->save();
                } elseif (strpos($attributeData->getAttributeCode(), 'payment_source') !== false &&
                        strpos($groupData->getGroupCode(), 'payment') !== false
                    ) {
                    $assignData = [
                        'group_id' => $groupData->getId(),
                        'attribute_id' => $attributeData->getId()
                    ];
                    $this->vendorRegistrationAssignGroupFactory->create()->setData($assignData)->save();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
