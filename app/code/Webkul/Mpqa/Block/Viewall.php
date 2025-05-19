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
 * Webkul Mpqa Viewall Block
 */
class Viewall extends \Magento\Framework\View\Element\Template
{
    private $question;

    private $answer;

    private $review;

    private $registry;

    private $request;

    private $mpproduct;

    private $product;

    private $imagehelper;

    private $questionCollection;

    /**
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Webkul\Mpqa\Model\ReviewFactory $reviewFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\Marketplace\Model\ProductFactory $mpproductFactory
     * @param \Magento\Catalog\Model\ProductFactory $productloader
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Webkul\Mpqa\Model\ReviewFactory $reviewFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\Marketplace\Model\ProductFactory $mpproductFactory,
        \Magento\Catalog\Model\ProductFactory $productloader,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\App\ResourceConnection $resource,
        \Webkul\Mpqa\Helper\Data $mpqaHelper,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->question = $questionFactory;
        $this->answer = $answerFactory;
        $this->review = $reviewFactory;
        $this->registry = $registry;
        $this->request=$context->getRequest();
        $this->mpproduct = $mpproductFactory;
        $this->product = $productloader;
        $this->imagehelper = $imageHelper;
        $this->resource = $resource;
        $this->mpqaHelper = $mpqaHelper;
        $this->mpHelper = $mpHelper;
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
        if (!$this->questionCollection) {
            $collection = $this->question
                ->create()->getCollection()
                ->addFieldToFilter('product_id', $this->getRequest()->getParam('id'))
                ->addFieldToFilter('status', 1);
            $data = $this->getParams();
            if (isset($data['q'])) {
                $collection->addFieldToFilter(
                    ['content','subject'],
                    [
                        ['like'=>'%'.$data['q'].'%'],
                        ['like'=>'%'.$data['q'].'%']
                    ]
                );
            }
            if (isset($data['helpful'])) {
                $q_id = [];
                foreach ($collection as $key) {
                    $q_id[] = $key->getQuestionId();
                }
                $answer = $this->answer->create()->getCollection()
                            ->addFieldToFilter('question_id', ['in'=>$q_id]);
                $answer->getSelect()
                ->join(
                    ['qa_rev' => $this->resource->getTableName('mp_qarespondreview')],
                    "main_table.answer_id = qa_rev.answer_id",
                    ["count"=>"COUNT(qa_rev.like_dislike)",
                    "question_id"=>"main_table.question_id"
                    ]
                )
                ->group('main_table.question_id')
                ->where("qa_rev.like_dislike = 1");

                $answer->setOrder('count', 'DESC');
                $q_id = [];
                foreach ($answer as $key) {
                    $q_id[] = $key->getQuestionId();
                }

                $collection = $this->question->create()->getCollection();
                $collection->addFieldToFilter('question_id', ['in'=>$q_id]);
            }
            if (isset($data['recent'])) {
                $collection->setOrder('question_id', 'DESC');
            }
            $this->questionCollection = $collection;
        }
        return $this->questionCollection;
    }

    public function getQuestionsCount()
    {
        $collection = $this->question
            ->create()->getCollection()
            ->addFieldToFilter('product_id', $this->getCurrentProduct()->getId())
            ->addFieldToFilter('status', 1);
        return ($collection->getSize());
    }

    public function getAnswersCount()
    {
        $id = [];
        $question=$this->getQuestions();
        foreach ($question as $key) {
            $id[] = $key->getQuestionId();
        }
        $collection = $this->answer
            ->create()->getCollection()
            ->addFieldToFilter('question_id', ["in"=>$id]);
        return ($collection->getSize());
    }

    public function getSellerId()
    {
        $collection=$this->mpproduct->create()->getCollection()
                ->addFieldToFilter('mageproduct_id', $this->getRequest()->getParam('id'));
        if ($collection->getSize()>0) {
            foreach ($collection as $seller) {
                $id=$seller->getSellerId();
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
                'mpqa.mpqa.list.pager'
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

    public function getCurrentProduct()
    {
        return $this->product->create()->load($this->getRequest()->getParam('id'));
    }

    public function getQuestionAnswers($qid)
    {
        return $this->answer->create()->getCollection()
            ->addFieldToFilter('question_id', $qid)
            ->setPageSize(1);
    }

    public function getReview($id)
    {
        return $this->review->create()->getCollection()->addFieldToFilter('answer_id', $id);
    }

    public function imageHelperObj()
    {
        return $this->imagehelper;
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
     * Get Mpqa Helper Data
     */
    public function getMpqaHelper()
    {
        return $this->mpqaHelper;
    }

    /**
     * Get Mp Helper Data
     */
    public function getMpHelper()
    {
        return $this->mpHelper;
    }

    /**
     * Get Params
     */
    public function getParamsRequest()
    {
        return $this->getRequest();
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
}
