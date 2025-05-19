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

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpgradeDataPatch implements DataPatchInterface
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
     * Undocumented function
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationGroupFactory $vendorRegistrationGroupFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAttributeFactory $vendorRegistrationAttributeFactory
     * @param \Webkul\MpVendorRegistration\Model\VendorRegistrationAssignGroupFactory
     *  $vendorRegistrationAssignGroupFactory
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
                'group_name' => 'On The Social Web',
                'sort_order' => '4',
                'group_status' => '0',
                'group_by_admin' => '1',
                'show_in_frontend' => '0',
                'group_code' => 'socialgroup'
            ]
        ];
        
        foreach ($data as $vendorGroup) {
            $this->vendorRegistrationGroupFactory->create()->setData($vendorGroup)->save();
        }

        $vendorAttributeSocialData = [
            [
                'attribute_code' => 'twitter_id',
                'attribute_label' => 'Twitter ID',
                'attribute_id' => '',
                'sort_order' => '1',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'facebook_id',
                'attribute_label' => 'Facebook ID',
                'attribute_id' => '',
                'sort_order' => '2',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'gplus_id',
                'attribute_label' => 'Google Plus ID',
                'attribute_id' => '',
                'sort_order' => '3',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'youtube_id',
                'attribute_label' => 'Youtube ID',
                'attribute_id' => '',
                'sort_order' => '4',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'vimeo_id',
                'attribute_label' => 'Vimeo ID',
                'attribute_id' => '',
                'sort_order' => '5',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'instagram_id',
                'attribute_label' => 'Instagram ID',
                'attribute_id' => '',
                'sort_order' => '6',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ],
            [
                'attribute_code' => 'pinterest_id',
                'attribute_label' => 'Pinterest ID',
                'attribute_id' => '',
                'sort_order' => '7',
                'is_required' => 0,
                'attribute_status' => 1,
                'show_in_frontend' => '1',
                'attribute_by_admin' => 1
            ]
        ];

        foreach ($vendorAttributeSocialData as $socialFields) {
            $this->vendorRegistrationAttributeFactory->create()->setData($socialFields)->save();
        }

        $groupCollection = $this->vendorRegistrationGroupFactory->create()->getCollection();
        $attributeCollection = $this->vendorRegistrationAttributeFactory->create()->getCollection();
        
        foreach ($attributeCollection as $attributeData) {
            $assignData = [];
            
            foreach ($groupCollection as $groupData) {
                if (strpos($attributeData->getAttributeCode(), '_id') !== false &&
                    strpos($groupData->getGroupCode(), 'socialgroup') !== false
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
