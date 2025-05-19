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
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\TestFramework\ErrorLog\Logger;

class Reviewanswer extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    protected $_review;

    protected $_customerSession;

    protected $_helper;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Webkul\Mpqa\Model\ReviewFactory $reviewFactory
     * @param \Webkul\Mpqa\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Webkul\Mpqa\Model\ReviewFactory $reviewFactory,
        \Webkul\Mpqa\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_review=$reviewFactory;
        $this->_customerSession=$customerSession;
        $this->_helper = $helper;
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
        $result = 0;
        $action = 0;

        try {
             $model=$this->_review->create()->getCollection()
                ->addFieldToFilter('answer_id', $data['ansid'])
                ->addFieldToFilter('review_from', $data['custid']);

            foreach ($model as $key) {
                $id=$key->getReviewId();
            }
            if (isset($id)) {
                $model=$this->_review->create()->load($id);
                if ($data['action']=='like' && $model->getLikeDislike()!=1) {
                    $model->setLikeDislike('1');
                    $result = 1;
                    $action = 1;
                }
                if ($data['action']=='dislike' && $model->getLikeDislike()!=0) {
                    $model->setLikeDislike('0');
                    $result = 1;
                    $action = 1;
                }
                $model->setId($id);
                $model->save();
            } else {
                $model=$this->_review->create();
                $model->setAnswerId($data['ansid'])
                       ->setReviewFrom($data['custid']);
                if ($data['action']=='like') {
                    $model ->setLikeDislike('1');
                    $result = 1;
                }
                if ($data['action']=='dislike') {
                    $model->setLikeDislike('0');
                    $result = 1;
                }
                $model->save();
            }
            $final_array=["action"=>$action , "action_result"=>$result];
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $final_array=["action"=>'error' , "action_result"=>$e->getMessage()];
        }

        //create json response
        $resultJson = $this->_resultJsonFactory->create();
        $resultJson->setData($final_array);
        return $resultJson;
    }
}
