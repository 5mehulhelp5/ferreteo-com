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
 * Webkul Mpqa Mpqaproductquestion Block
 */
class Mpqaproductquestion extends \Magento\Framework\View\Element\Template
{
    private $question;

    private $answer;

    private $review;

    private $registry;

    private $request;

    private $mpproduct;

    private $date;

    private $questionList;

    /**
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Webkul\Mpqa\Model\ReviewFactory $reviewFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\Marketplace\Model\ProductFactory $mpproductFactory
     * @param array $data
     */
    public function __construct(
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Webkul\Mpqa\Model\ReviewFactory $reviewFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\Marketplace\Model\ProductFactory $mpproductFactory,
        \Magento\Framework\Session\StorageInterface $storage,
        \Webkul\Mpqa\Helper\Data $mpqaHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->question = $questionFactory;
        $this->answer = $answerFactory;
        $this->review = $reviewFactory;
        $this->registry = $registry;
        $this->request = $context->getRequest();
        $this->mpproduct = $mpproductFactory;
        $this->date = $date;
        $this->storage = $storage;
        $this->mpqaHelper = $mpqaHelper;
        $this->jsonHelper = $jsonHelper;
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
        if (!$this->questionList) {
            $collection = $this->question
                ->create()->getCollection()
                ->addFieldToFilter('product_id', $this->getCurrentProductId())
                ->addFieldToFilter('status', 1);
            $this->questionList = $collection;
        }
        return $this->questionList;
    }

    /**
     * [getProductMpqaUrl description]
     *
     * @return  [type]  [return description]
     */
    public function getProductMpqaUrl()
    {
        return $this->getUrl(
            'mpqa/mpqaquest/ajaxlistview',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getCurrentProductId(),
            ]
        );
    }

    /**
     * get questions count
     * @return int
     */
    public function getQuestionsCount()
    {
        $collection = $this->getQuestions();
        return ($collection->getSize());
    }

    /**
     * get answers count
     * @return int
     */
    public function getAnswersCount()
    {
        $id = [];
        $question = $this->getQuestions();
        foreach ($question as $key) {
            $id[] = $key->getQuestionId();
        }
        $collection = $this->answer
            ->create()->getCollection()
            ->addFieldToFilter('question_id', ["in"=>$id]);
        return $collection->getSize();
    }

    /**
     * get seller id
     * @return int
     */
    public function getSellerId()
    {
        $collection = $this->mpproduct->create()->getCollection()
                ->addFieldToFilter('mageproduct_id', $this->getCurrentProductId());
        if ($collection->getSize()>0) {
            foreach ($collection as $seller) {
                $id = $seller->getSellerId();
            }
            return $id;
        }
        return 0;
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
                'mpqa.product.question.pager'
            )
            ->setCollection(
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
     * Get Product Id
     */
    public function getCurrentProductId()
    {
        $product = $this->registry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * get answer of question
     * @return object
     */
    public function getQuestionAnswers($qid)
    {
        return $this->answer->create()->getCollection()
            ->addFieldToFilter('question_id', $qid)
            ->setPageSize(1);
    }

    /**
     * get count of answer for each question
     * @return count
     */
    public function getAnswerCount($qid)
    {
        $collection = $this->answer->create()->getCollection()
            ->addFieldToFilter('question_id', $qid);
        return $collection->getSize();
    }

    /**
     * return the feedback collection for the answer
     *
     * @param [int] $id
     * @return void
     */
    public function getReview($id)
    {
        return $this->review->create()->getCollection()->addFieldToFilter('answer_id', $id);
    }

    /**
     * Retrieve customer id from current session
     *
     * @api
     * @return int|null
     */
    public function getCustomerId()
    {
        if ($this->storage->getData('customer_id')) {
            return $this->storage->getData('customer_id');
        }
        return null;
    }

    /**
     * Get IsSecure
     */
    public function getIsSecure()
    {
        return $this->getRequest()->isSecure();
    }

    /**
     * Get Json Helper Data
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }

    /**
     * Get Mpqa Helper Data
     */
    public function getMpqaHelper()
    {
        return $this->mpqaHelper;
    }
}
