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

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\Timezone;

/**
 * Webkul Mpqa Index Controller.
 */
class Saveanswer extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    private $answer;

    private $helper;

    private $mphelper;

    private $customerRepository;

    private $question;

    private $product;

    private $storeManager;

    private $datetime;

    private $scopeConfig;

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

    private $timezone;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Webkul\Marketplace\Helper\Data $mphelper
     * @param \Webkul\Mpqa\Helper\Data $helper
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param Timezone $timezone
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Webkul\Marketplace\Helper\Data $mphelper,
        \Webkul\Mpqa\Helper\Data $helper,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        Timezone $timezone
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->answer=$answerFactory;
        $this->mphelper=$mphelper;
        $this->helper=$helper;
        $this->question=$questionFactory;
        $this->product=$product;
        $this->storeManager=$storeManager;
        $this->customerRepository = $customerRepository;
        $this->resultJsonFactory=$resultJsonFactory;
        $this->datetime = $datetime;
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * Mpqa page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest()) || !$this->getRequest()->isPost()) {
            $data = $this->resultJsonFactory->create();
            $result['status'] = false;
            $result = $data->setData($result);
            return $result;
        }
        $data = $this->getRequest()->getParams();
        $question=$this->question->create()->load($data['qid']);
        $seller_id = $question->getSellerId();

        if ($data['cid'] == $seller_id && $this->mphelper->isSeller()) {
            $time = $this->datetime->date();
            $data['respond_type'] = 'Seller';
            $data['nickname'] = 'Seller';
            $data['time'] = $this->timezone->formatDate($time, \IntlDateFormatter::MEDIUM, true);
            $data['ans'] = strip_tags($data['ans']);
            $model = $this->answer->create();
            try {
                $model->setQuestionId($data['qid'])
                    ->setRespondFrom($data['cid'])
                    ->setRespondType($data['respond_type'])
                    ->setRespondNickname("Seller")
                    ->setContent($data['ans'])
                    ->setStatus(1)
                    ->setCreatedAt($time);
                $id=$model->save()->getId();
                $data["id"]=$id;
                if (isset($id)) {   //send response mail
                    $data['status'] = true;
                    $this->messageManager->addSuccess(__("Response saved successfully."));
                    $customer_id = $question->getBuyerId();
                    $seller_id = $question->getSellerId();
                    $p_id=$question->getProductId();
                    $adminStoremail = $this->mphelper->getAdminEmailId();
                    $adminEmail=$adminStoremail? $adminStoremail:$this->mphelper->getDefaultTransEmailId();
                    $adminUsername = 'Admin';
                    $customers=$this->customerRepository->getById($customer_id);
                    $customer_name=$customers->getFirstName()." ".$customers->getLastName();
                    $product=$this->product->create()->load($p_id);
                    $product_name=$product->getName();
                    $url = $product->getProductUrl();
                    $msg = __('You have got a new response on your Question.');
                    $templateVars = [
                                        'store' => $this->storeManager->getStore(),
                                        'customer_name' => $customer_name,
                                        'link'          =>  $url,
                                        'product_name'  => $product_name,
                                        'message'   => $msg
                                    ];
                    $to = [$customers->getEmail()];
                    $from = ['email' => $adminEmail, 'name' => 'Admin'];
                    $this->helper->sendResponseMail($templateVars, $from, $to);
                    // mail to admin
                    $admin_email = $this->scopeConfig->getValue(
                        'mpqa/general_settings/responseto_admin',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );
                    if ($admin_email) {
                        $msg = __('Seller replied to a query on his product %1.', $product_name);
                        $customers=$this->customerRepository->getById($seller_id);
                        $customer_name=$customers->getFirstName()." ".$customers->getLastName();
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
                } else {
                    $this->messageManager->addError(__("Error while saving response."));
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__("Error while saving response."));
            }
        } else {
            $this->messageManager->addError(__("You are not authenticated to answer this question."));
            $data['status'] = false;
            $result = $this->resultJsonFactory->create();
            $result->setData($data);
            return $result;
        }
        $result = $this->resultJsonFactory->create();
        $result->setData($data);
        return $result;
    }
}
