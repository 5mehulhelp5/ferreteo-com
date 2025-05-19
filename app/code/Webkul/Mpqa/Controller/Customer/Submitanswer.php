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

class Submitanswer extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    private $answer;

    private $question;

    private $helper;

    private $mphelper;

    private $product;

    private $customerSession;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Core form key validator
     *
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \Webkul\Marketplace\Helper\Data $mphelper
     * @param \Webkul\Mpqa\Helper\Data $helper
     * @param \Magento\Customer\Api\CustomerRepositoryInterface\Proxy $customerRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Magento\Catalog\Model\ProductFactory $product,
        \Webkul\Marketplace\Helper\Data $mphelper,
        \Webkul\Mpqa\Helper\Data $helper,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->answer=$answerFactory;
        $this->mphelper=$mphelper;
        $this->helper=$helper;
        $this->question=$questionFactory;
        $this->product=$product;
        $this->storeManager=$storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->resultJsonFactory=$resultJsonFactory;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Check customer authentication.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->customerSession->isLoggedIn()) {
            $data = $this->resultJsonFactory->create();
            $result['status'] = 0;
            $result = $data->setData($result);
            return $result;
        }

        return parent::dispatch($request);
    }

    /**
     * Mpqa page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if (!$this->getRequest()->isPost()) {
            $data = $this->resultJsonFactory->create();
            $result['status'] = false;
            $result = $data->setData($result);
            return $result;
        }
        $time = date('Y-m-d H:i:s');
        if ($data['customer_id'] == $this->mphelper->getCustomerId()) {
            if ($data['seller_id'] == $data['customer_id']) {
                $data['respond_type'] = 'Seller';
            } else {
                $data['respond_type'] = 'Customer';
            }
            $data['qa_nickname'] = strip_tags($data['qa_nickname']);
            $data['qa_answer'] = strip_tags($data['qa_answer']);
            $model=$this->answer->create();
            $model->setQuestionId($data['question_id'])
                ->setRespondFrom($data['customer_id'])
                ->setRespondType($data['respond_type'])
                ->setRespondNickname($data['qa_nickname'])
                ->setContent($data['qa_answer'])
                ->setStatus(2)
                ->setCreatedAt($time);
            $id=$model->save()->getId();
            if (isset($id)) {   //send response mail
                $question=$this->question->create()->load($data['question_id']);
                $buyer_id=$question->getBuyerId();
                $seller_id = $question->getSellerId();
                $p_id=$question->getProductId();
                $adminStoremail = $this->mphelper->getAdminEmailId();
                $adminEmail=$adminStoremail? $adminStoremail:$this->mphelper->getDefaultTransEmailId();
                $adminUsername = 'Admin';
                $buyer=$this->customerRepository->getById($buyer_id);
                $buyer_name=$buyer->getFirstName()." ".$buyer->getLastName();
                $customers = $this->customerRepository->getById($data['customer_id']);
                $customer_name = $customers->getFirstName()." ".$customers->getLastName();
                $product=$this->product->create()->load($p_id);
                $product_name=$product->getName();
                $url= $product->getProductUrl();
                $msg= 'You have got a new response on your Question.';
                $templateVars = [
                                'store' => $this->storeManager->getStore(),
                                'customer_name' => $buyer_name,
                                'link'          =>  $url,
                                'product_name'  => $product_name,
                                'message'   => $msg
                            ];
                $to=[$buyer->getEmail()];
                $from=$from = ['email' => $adminEmail, 'name' => 'Admin'];
                $this->helper->sendResponseMail($templateVars, $from, $to);

                $seller_email = $this->scopeConfig->getValue(
                    'mpqa/general_settings/responseto_seller',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );

                if ($seller_id && $seller_email) { //mail to seller
                    $seller = $this->customerRepository->getById($seller_id);
                    $seller_name = $seller->getFirstName()." ".$seller->getLastName();
                    $seller_url= $this->_url->getUrl('mpqa/mpqaquest/giveanswer').'id/'.$data['question_id'];
                    $msg = __('%1 replied to a query on your product.', $customer_name);
                    $templateVars = [
                                        'store' => $this->storeManager->getStore(),
                                        'customer_name' => $seller_name,
                                        'link'          =>  $seller_url,
                                        'product_name'  => $product_name,
                                        'message'   => $msg
                                    ];
                    $to = [$seller->getEmail()];
                    $from = ['email' => $adminEmail, 'name' => 'Admin'];
                    $this->helper->sendResponseMail($templateVars, $from, $to);
                }

                // mail to admin
                $admin_email = $this->scopeConfig->getValue(
                    'mpqa/general_settings/responseto_admin',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
                if ($admin_email) {
                    $msg = __('%1 replied to a query on product %2.', $customer_name, $product_name);
                    $templateVars = [
                                        'store' => $this->storeManager->getStore(),
                                        'customer_name' => __('Admin'),
                                        'link'          =>  $url,
                                        'product_name'  => $product_name,
                                        'message'   => $msg
                                    ];
                    $to = [$adminEmail];
                    $from = ['email' => $customers->getEmail(), 'name' => $customer_name];
                    $this->helper->sendResponseMail($templateVars, $from, $to);
                }
            }
            $final_array = ['status' => true, 'id' => $id];
        } else {
            $final_array = ['status' => false];
        }
        $result = $this->resultJsonFactory->create();
        $result->setData($final_array);
        return $result;
    }
}
