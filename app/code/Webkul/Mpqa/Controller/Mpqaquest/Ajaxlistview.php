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

namespace Webkul\Mpqa\Controller\Mpqaquest;
 
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
 
class Ajaxlistview extends Action
{
 
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Webkul\Mpqa\Model\ResourceModel\Mpqaanswer\CollectionFactory $answerFactory,
        \Webkul\Mpqa\Model\ResourceModel\Question\CollectionFactory $questionFactory,
        \Webkul\Mpqa\Model\ReviewFactory $reviewFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->timezone = $timezone;
        $this->answer = $answerFactory;
        $this->question = $questionFactory;
        $this->review=$reviewFactory;
        $this->resource = $resource;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $pid = $this->getRequest()->getParam('id');
        
        $QuesArray = $this->question->create()
        ->addFieldToFilter('status', ['eq' => 1])
        ->addFieldToFilter('product_id', ['eq' => $pid]);
        $QuesArray = $QuesArray->getData();
        $QuestionData = [];
        $AnswerData = [];
        foreach ($QuesArray as $key => $QuesItem) {
            $QuestionId = $QuesItem['question_id'];
            $AnsArray = $this->answer->create()
            ->addFieldToFilter('question_id', $QuestionId);
            $AnsArray->getSelect()->join(
                ['qa_rev' => $this->resource->getTableName('mp_qarespondreview')],
                "main_table.answer_id = qa_rev.answer_id"
            )
                ->where("qa_rev.like_dislike = 1");
            $QuesItem['Ans'] = $AnsArray->getData();
            $QuesItem['TotalAns'] = $AnsArray->getSize();
            $QuestionData['Ques'][] = $QuesItem;
        }
        $result->setData($QuestionData);
        return $result;
    }
}
