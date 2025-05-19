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
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Test\Unit\Block;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as EavCollection;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\DB\Select;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\ProductFinder\Block\Finder;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Helper\Image as HelperImage;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter as ResourceFilter;
use Mageplaza\ProductFinder\Model\ResourceModel\Filter\CollectionFactory as FilterCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Options\CollectionFactory as OptionsCollection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\Collection;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule\CollectionFactory as RuleCollection;
use Mageplaza\ProductFinder\Model\Rule;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class RuleTest
 * @package Mageplaza\ProductFinder\Test\Unit\Block
 */
class RuleTest extends TestCase
{
    /**
     * @var RuleCollection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleCollection;

    /**
     * @var FilterCollection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $filterCollection;

    /**
     * @var ResourceFilter|PHPUnit_Framework_MockObject_MockObject
     */
    protected $resourceFilter;

    /**
     * @var EavCollection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $eavCollection;

    /**
     * @var HelperData|PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperData;

    /**
     * @var OptionsCollection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $optionsCollection;

    /**
     * @var Registry|PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreRegistry;

    /**
     * @var Context|PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var FormKey|PHPUnit_Framework_MockObject_MockObject
     */
    protected $formKey;

    /**
     * @var HelperImage|PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperImage;

    /**
     * @var Select|PHPUnit_Framework_MockObject_MockObject
     */
    protected $selectMock;

    /**
     * @var Http|PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var Finder
     */
    protected $block;

    protected function setUp()
    {
        $this->ruleCollection    = $this->getMockBuilder(RuleCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->filterCollection  = $this->getMockBuilder(FilterCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resourceFilter    = $this->getMockBuilder(ResourceFilter::class)
            ->disableOriginalConstructor()->getMock();
        $this->eavCollection     = $this->getMockBuilder(EavCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->helperData        = $this->getMockBuilder(HelperData::class)
            ->disableOriginalConstructor()->getMock();
        $this->optionsCollection = $this->getMockBuilder(OptionsCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->coreRegistry      = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()->getMock();
        $this->context           = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()->getMock();
        $this->formKey           = $this->getMockBuilder(FormKey::class)
            ->disableOriginalConstructor()->getMock();
        $this->helperImage       = $this->getMockBuilder(HelperImage::class)
            ->disableOriginalConstructor()->getMock();
        $this->request           = $this->getMockBuilder(Http::class)
            ->setMethods(['getFullActionName'])
            ->disableOriginalConstructor()->getMock();

        $this->block = new Finder(
            $this->ruleCollection,
            $this->filterCollection,
            $this->resourceFilter,
            $this->eavCollection,
            $this->helperData,
            $this->optionsCollection,
            $this->coreRegistry,
            $this->context,
            $this->formKey,
            $this->helperImage,
            []
        );
    }

    public function testAdminInstance()
    {
        $this->assertInstanceOf(Finder::class, $this->block);
    }

    /**
     * @param string $actionName
     *
     * @dataProvider getParams
     */
    public function testGetRuleCollection($actionName)
    {
        $position   = 'top';
        $categoryId = 3;

        $ruleModel = $this->getMockBuilder(Rule::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getRuleId',
                'getName',
                'getStatus',
                'getTemplate',
                'getMode',
                'getPosition',
                'getCategoriesIds',
                'getSortOrder'
            ])
            ->getMock();

        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->ruleCollection->method('create')->willReturn($collection);

        $collection->expects($this->at(1))->method('addFieldToFilter')
            ->with('position', $position)
            ->willReturn([$ruleModel]);
        $collection->expects($this->at(1))->method('addFieldToFilter')
            ->with('status', true)
            ->willReturn([$ruleModel]);
        $collection->expects($this->at(1))->method('setOrder')
            ->with('sort_order', 'asc')
            ->willReturn([$ruleModel]);

        $this->block->setData('position', $position);
        $this->request->method('getFullActionName')->willReturn($actionName);
        if ($actionName === 'catalog_category_view') {
            $category = $this->getMockBuilder(Category::class)
                ->disableOriginalConstructor()
                ->getMock();
            $category->method('getId')->willReturn($categoryId);
            $this->coreRegistry->method('registry')->with('current_category')->willReturn($category);
            $collection->expects($this->at(2))->method('addFieldToFilter')
                ->with('categories_ids', [['null' => true], ['finset' => $categoryId]])
                ->willReturn([$ruleModel]);
        } else {
            $collection->expects($this->at(2))->method('addFieldToFilter')
                ->with('categories_ids', [['null' => true]])
                ->willReturn([$ruleModel]);
        }

        $this->assertEquals([$ruleModel], $this->block->getRuleCollection());
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
            ['catalog_product_view'],
            ['catalog_category_view'],
            ['cms_index_index']
        ];
    }
}
