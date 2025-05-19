<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Mpqa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Mpqa\Block;

/**
 * Webkul Mpqa Mpqaquestion Block
 */
class Mpqaquestion extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $urlinterface;

    protected $question;

    protected $imageHelper;

    protected $mphelper;

    protected $questionCollection;

    /**
     * @param \Webkul\Marketplace\Helper\Data $mphelper
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Webkul\Marketplace\Helper\Data $mphelper,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product $product,
        array $data = []
    ) {
        $this->mphelper = $mphelper;
        $this->urlinterface = $context->getUrlBuilder();
        $this->question=$questionFactory;
        $this->product = $product;
        $this->imageHelper = $context->getImageHelper();
        parent::__construct($context, $data);
    }

    /**
     * get parameters
     * @return array
     */
    public function getParams()
    {
        $data = $this->getRequest()->getParams();
        return $data;
    }

    /**
     * get questions
     * @return object
     */
    public function getQuestions()
    {
        if (!$this->questionCollection) {
            $sellerId = $this->mphelper->getCustomerId();
            $collection = $this->question
                ->create()->getCollection()
                ->addFieldToFilter('seller_id', $sellerId)
                ->addFieldToFilter('status', 1)
                ->setOrder('question_id', "DESC");
            $this->questionCollection = $collection;
        }
        return $this->questionCollection;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getQuestions()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'marketplace.product.list.pager'
            )->setCollection(
                $this->getQuestions()
            );
            $this->setChild('pager', $pager);
            $this->getQuestions()->load();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get Image Helper
     */
    public function imageHelperObj()
    {
        return $this->imageHelper;
    }

    /**
     * Get Product Data By Id
     */
    public function getProductData($id)
    {
        return $this->product->load($id);
    }
}
