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

namespace Webkul\Mpqa\Controller\Adminhtml\Mpqa;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;

class Giveanswer extends Action
{
     /**
      * @var \Magento\Framework\View\Result\PageFactory
      */
    private $resultPageFactory;

    private $answer;

    private $timezone;

    /**
     * @var \Magento\Backend\Model\View\Result\Page
     */
    private $resultPage;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    private $qahelper;

    private $mphelper;

    private $customerRepository;

    private $question;

    private $product;

    private $storeManager;

    /**
     * Core form key validator
     *
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Timezone $timezone
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Webkul\Mpqa\Helper\Data $qahelper
     * @param \Webkul\Marketplace\Helper\Data $mphelper
     * @param \Magento\Customer\Api\CustomerRepositoryInterface\Proxy $customerRepository
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Timezone $timezone,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Webkul\Mpqa\Helper\Data $qahelper,
        \Webkul\Marketplace\Helper\Data $mphelper,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->qahelper = $qahelper;
        $this->mphelper = $mphelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->answer = $answerFactory;
        $this->question = $questionFactory;
        $this->customerRepository = $customerRepository;
        $this->product = $product;
        $this->storeManager = $storeManager;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->timezone = $timezone;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Giveanswer page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest()) || !$this->getRequest()->isPost()) {
            $data = $this->resultJsonFactory->create();
            $result['status'] = false;
            $result = $data->setData($result);
            return $result;
        }
        $data=$this->getRequest()->getParams();
        $time = date('Y-m-d H:i:s');
        $data['respond_type']='Admin';
        $data['nickname']='Admin';
        $data['time'] = $this->timezone->formatDate($time, \IntlDateFormatter::MEDIUM, true);
        $data['content'] = strip_tags($data['content']);
        $model=$this->answer->create();
        $model->setQuestionId($data['pid'])
            ->setRespondFrom(0)
            ->setRespondType($data['respond_type'])
            ->setRespondNickname($data['nickname'])
            ->setContent($data['content'])
            ->setStatus(1)
            ->setCreatedAt($time);
        $id=$model->save()->getId();
        $data['answer_id'] = $id;
        if (isset($id)) {   //send response mail
            $question = $this->question->create()->load($data['pid']);
            $customer_id = $question->getBuyerId();
            $seller_id = $question->getSellerId();
            $p_id = $question->getProductId();
            $adminStoremail = $this->mphelper->getAdminEmailId();
            $adminEmail = $adminStoremail ? $adminStoremail:$this->mphelper->getDefaultTransEmailId();
            $adminUsername = 'Admin';
            $customers = $this->customerRepository->getById($customer_id);
            $customer_name = $customers->getFirstName()." ".$customers->getLastName();
            $product = $this->product->create()->load($p_id);
            $product_name = $product->getName();
            $url = $product->getProductUrl();
            $msg = __('You have got a new response on your question.');
            $templateVars = [
                                'store' => $this->storeManager->getStore(),
                                'customer_name' => $customer_name,
                                'link'          =>  $url,
                                'product_name'  => $product_name,
                                'message'   => $msg
                            ];
            $to = [$customers->getEmail()];
            $from = ['email' => $adminEmail, 'name' => 'Admin'];
            $this->qahelper->sendResponseMail($templateVars, $from, $to);

            $seller_email = $this->scopeConfig->getValue(
                'mpqa/general_settings/responseto_seller',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            if ($seller_id && $seller_email) { //mail to seller
                $customers = $this->customerRepository->getById($seller_id);
                $customer_name = $customers->getFirstName()." ".$customers->getLastName();
                $msg = __('Admin replied to a query on your product.');
                $templateVars = [
                                    'store' => $this->storeManager->getStore(),
                                    'customer_name' => $customer_name,
                                    'link'          =>  $url,
                                    'product_name'  => $product_name,
                                    'message'   => $msg
                                ];
                $to = [$customers->getEmail()];
                $from = ['email' => $adminEmail, 'name' => 'Admin'];
                $this->qahelper->sendResponseMail($templateVars, $from, $to);
            }
        }
        $data['status'] = true;
        $result = $this->resultJsonFactory->create();
        $result->setData($data);
        return $result;
    }
}
