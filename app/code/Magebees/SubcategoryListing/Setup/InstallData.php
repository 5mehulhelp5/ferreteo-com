<?php
namespace Magebees\SubcategoryListing\Setup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'show_subcategories_listing',
            [
                'type' => 'int',
                'label' => 'Show Subcategory Listing For This Category',
                'input' => 'boolean',
                'sort_order' => 300,
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 1,
                'group' => 'Subcategory Listing',
                'backend' => '',
                'note' => 'This option for Parent Category'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'subcategory_listing_size',
            [
                'type' => 'varchar',
                'label' => 'Max Sub-Categories Per Row',
                'input' => 'text',
                'sort_order' => 350,
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 4,
                'group' => 'Subcategory Listing',
                'backend' => ''
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'cat_thumbnail_img',
            [
                'type' => 'varchar',
                'label' => 'Thumbnail',
                'input' => 'image',
                'sort_order' => 400,
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'Subcategory Listing',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'mbsubcatlistlocation',
            [
                'type' => 'int',
                'label' => 'Listing Location',
                'input' => 'select',
                'sort_order' => 450,
                'source' => 'Magebees\SubcategoryListing\Model\Category\Attribute\Source\Mbsubcatlistlocation',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 1,
                'group' => 'Subcategory Listing',
                'backend' => ''
            ]
        );
    }
}