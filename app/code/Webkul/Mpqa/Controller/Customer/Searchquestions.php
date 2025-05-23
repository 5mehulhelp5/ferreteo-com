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
namespace Webkul\Mpqa\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;

class Searchquestions extends Action
{
    /**
     * @var PageFactory
     */

    protected $_resultPageFactory;
    protected $_question;
    protected $_answer;
    protected $_review;
    protected $_timezone;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Timezone $timezone,
        \Webkul\Mpqa\Model\ReviewFactory $reviewFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_question = $questionFactory;
        $this->_answer=$answerFactory;
        $this->_review=$reviewFactory;
        $this->_timezone = $timezone;
        $this->_resultJsonFactory=$resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Mpqa page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if ($data) {
            $response=[];
            $final_array=[];
            $questions=$this->_question->create()->getCollection()
                ->addFieldToFilter(
                    ['content','subject'],
                    [
                            ['like'=>'%'.$data['query'].'%'],
                            ['like'=>'%'.$data['query'].'%']
                        ]
                )
                ->addFieldToFilter('product_id', $data['pid']);
            $questions->addFieldToFilter('status', 1);

            foreach ($questions as $key) {
                $answers=$this->getQuestionAnswers($key->getQuestionId());
                if ($answers->getSize()>0) {
                    foreach ($answers as $ans) {
                        $likes=0;
                        $dislikes=0;
                        $reviews=$this->getReview($ans->getAnswerId());
                        $like='like';
                        $dislike='dislike';
                        foreach ($reviews as $rev) {
                            $returnData = $this->
                            getForeachData($rev, $likes, $dislikes, $key, $like, $dislike);
                            $likes = $returnData[0];
                            $dislikes = $returnData[1];
                            $like = $returnData[2];
                            $dislike = $returnData[3];
                        }
                        $response['likes']=$likes;
                        $response['dislikes']=$dislikes;
                        $response['like_class']=$like;
                        $response['dislike_class']=$dislike;
                        $response['answer_id']=$ans->getAnswerId();
                        $response['answer']=$ans->getContent();
                        $response['nickname']=$ans->getRespondNickname();
                        $response['createdat']=
                        $this->_timezone->formatDate(
                            $ans->getCreatedAt(),
                            \IntlDateFormatter::MEDIUM
                        );
                    }
                }
                $answer_count = $this->getAnswerCount($key->getQuestionId());
                $response['count'] = $answer_count;
                $response['qa_date']= $this->_timezone->formatDate($key->getCreatedAt(), \IntlDateFormatter::MEDIUM);
                $response['question_id']=$key->getQuestionId();
                $response['content']=$key->getContent();
                $response['subject']=$key->getSubject();
                $response['qa_nickname'] = $key->getQaNickname();
                array_push($final_array, $response);
            }
            $result = $this->_resultJsonFactory->create();
            $result->setData($final_array);
            return $result;
        }
    }

    public function getQuestionAnswers($qid)
    {
        return $this->_answer->create()->getCollection()
            ->addFieldToFilter('question_id', $qid)
            ->setPageSize(1);
    }

    public function getReview($id)
    {
        return $this->_review->create()->getCollection()->addFieldToFilter('answer_id', $id);
    }
    /**
     * get count of answer for each question
     * @return count
     */
    public function getAnswerCount($qid)
    {
        $collection = $this->_answer->create()->getCollection()
            ->addFieldToFilter('question_id', $qid);
        return $collection->getSize();
    }

    /**
     * [getForeachData description]
     *
     * @param $rev         [$rev description]
     * @param $likes       [$likes description]
     * @param $dislikes    [$dislikes description]
     * @param $customerId  [$customerId description]
     * @param $key         [$key description]
     * @param $like        [$like description]
     * @param $dislike     [$dislike description]
     *
     * @return             [$likes, $dislikes, $like, $dislike]
     */
    public function getForeachData($rev, $likes, $dislikes, $key, $like, $dislike)
    {
        if ($rev->getLikeDislike()==1) {
            $likes++;
        } else {
            $dislikes++;
        }
        if ($rev->getReviewFrom()==$this->getRequest()->getParam('custid')) {
            if ($key->getLikeDislike()==1) {
                $like='liked';
            } else {
                $dislike='disliked';
            }
        }
        return [$likes, $dislikes, $like, $dislike];
    }
}
