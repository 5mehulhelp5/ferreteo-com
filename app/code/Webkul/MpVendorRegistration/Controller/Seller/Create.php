<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Controller\Seller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;

class Create extends \Magento\Customer\Controller\Account\CreatePost
{
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Customer\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var \Magento\Customer\Model\Metadata\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    protected $regionDataFactory;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    protected $addressDataFactory;

    /**
     * @var \Magento\Customer\Model\Registration
     */
    protected $registration;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerDataFactory;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Customer\Model\CustomerExtractor
     */
    protected $customerExtractor;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlModel;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\Account\Redirect
     */
    private $accountRedirect;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;
    
    /**
     * @var  \Magento\Framework\Registry $registry,
     */
    protected $registry;
    /**
     * @var $CustomerRepository
     */
    protected $customerRepository;

    /**
     * Undocumented function
     *
     * @param Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param \Magento\Framework\UrlFactory $urlFactory
     * @param \Magento\Customer\Model\Metadata\FormFactory $formFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Customer\Api\Data\RegionInterfaceFactory $regionDataFactory
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magento\Customer\Model\Registration $registration
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Customer\Model\CustomerExtractor $customerExtractor
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Customer\Model\Account\Redirect $accountRedirect
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Webkul\MpVendorRegistration\Helper\Data $helper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieMetadataManager
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Helper\Address $addressHelper,
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Customer\Model\Metadata\FormFactory $formFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\Data\RegionInterfaceFactory $regionDataFactory,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Customer\Model\Registration $registration,
        \Magento\Framework\Escaper $escaper,
        \Magento\Customer\Model\CustomerExtractor $customerExtractor,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Customer\Model\Account\Redirect $accountRedirect,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Webkul\MpVendorRegistration\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieMetadataManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator = null,
        \Magento\Captcha\Helper\Data $captchHelper
    ) {
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        $this->customerRepository = $customerRepository;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieMetadataManager = $cookieMetadataManager;
        $this->customerExtractor = $customerExtractor;
        $this->accountManagement = $accountManagement;
        $this->addressHelper = $addressHelper;
        $this->formFactory = $formFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->regionDataFactory = $regionDataFactory;
        $this->addressDataFactory = $addressDataFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerUrl = $customerUrl;
        $this->registration = $registration;
        $this->urlModel = $urlFactory->create();
        $this->dataObjectHelper = $dataObjectHelper;
        $this->accountRedirect = $accountRedirect;
        $this->encryptor = $encryptor;
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->registry = $registry;
        $this->captchHelper = $captchHelper;
        $this->formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(
            \Magento\Framework\Data\Form\FormKey\Validator::class
        );
        parent::__construct(
            $context,
            $customerSession,
            $scopeConfig,
            $storeManager,
            $accountManagement,
            $addressHelper,
            $urlFactory,
            $formFactory,
            $subscriberFactory,
            $regionDataFactory,
            $addressDataFactory,
            $customerDataFactory,
            $customerUrl,
            $registration,
            $escaper,
            $customerExtractor,
            $dataObjectHelper,
            $accountRedirect,
            $customerRepository,
            $formKeyValidator
        );
    }

    /**
     * Create Supplier account action
     *
     * @return Json
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $result = [];
        $result["error"] = false;
        $result["msg"] = "";
        $result["reload"] = false;
        $result["redirect"] = '';

        if (!$this->getRequest()->isPost() || !$this->formKeyValidator->validate($this->getRequest())) {
            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] =__("Invalid request.");
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData($result);
        }

        try {
            $data = $this->getRequest()->getParams();
            if (isset($data["captcha"]) && is_array($data["captcha"])) {
                $formId = "wk_vendor_create_form";
                $captchaModel = $this->captchHelper->getCaptcha($formId);
                if (!$captchaModel->isCorrect($data["captcha"]["wk_vendor_create_form"])) {
                    return $this->returnJsonError(__('Incorrect CAPTCHA'));
                }
            }
            $this->customerSession->regenerateId();
            $address = $this->extractAddress();
            $addresses = $address === null ? [] : [$address];

            $customer = $this->customerExtractor->extract('customer_account_create', $this->_request);
            if ($addresses != null) {
                $customer->setAddresses($addresses);
            }
           
            $password = $this->getRequest()->getParam('password');
            $confirmation = $this->getRequest()->getParam('password_confirmation');
            $redirectUrl = $this->customerSession->getBeforeAuthUrl();

            $this->checkPasswordConfirmation($password, $confirmation);

            $this->registry->register('isSecureArea', true);
            
            $customer = $this->accountManagement
                ->createAccount($customer, $password, $redirectUrl);

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
            }

            $this->_eventManager->dispatch(
                'customer_register_success',
                ['account_controller' => $this, 'customer' => $customer]
            );

            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
            if ($confirmationStatus ===
                          \Magento\Customer\Api\AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());

                $this->messageManager->addSuccess(
                    __(
                        'You must confirm your account.'.' Please check your email for the confirmation
                         link or <a href="%1">click here</a> for a new link.',
                        $email
                    )
                );
                $result["authRequired"] = true;
                $result["msg"] = __(
                    'You must confirm your account. Please check your email for the '.'
                    confirmation link or <a href="%1">click here</a> for a new link.',
                    $email
                );
                $url = $this->urlModel->getUrl('customer/account/login', ['_secure' => true]);
                $result["error"] = true;
                $result["reload"] = true;
                $result["redirect"] = $url;
                $resultJson = $this->resultJsonFactory->create();
                return $resultJson->setData($result);
            } else {
                $url = $this->urlModel->getUrl('marketplace/account/dashboard', ['_secure' => true]);
                $this->customerSession->setCustomerDataAsLoggedIn($customer);
                $result["error"] = false;
                $result["reload"] = false;
                $result["msg"] = $this->getSuccessMessage();
                $result["redirect"] = $url;
            }
            if ($this->getCookieMetaDataManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieMetaDataManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        } catch (\Magento\Framework\Exception\StateException $e) {
            $url = $this->urlModel->getUrl('customer/account/forgotpassword');
            $message = __(
                'There is already an account with this email address. '.'
                If you are sure that it is your email address, <a href="%1">click here</a> to '.'
                get your password and access your account.',
                $url
            );

            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] = $message;
        } catch (\Magento\Framework\Exception\InputException $e) {
            $messageArr[] = $this->escaper->escapeHtml($e->getMessage());
            foreach ($e->getErrors() as $error) {
                $messageArr[] = $this->escaper->escapeHtml($error->getMessage());
            }

            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] = implode('\n', $messageArr);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $result["error"] = true;
            $result["reload"] = true;
            $result["msg"] = $this->escaper->escapeHtml($e->getMessage());
        } catch (\Exception $e) {
            $result["error"] = true;
            $result["reload"] = false;
            $result["msg"] = $e->getMessage();
        }

        $this->customerSession->setCustomerFormData($this->getRequest()->getPostValue());

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }

    /**
     * Retrieve cookie manager
     *
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieMetaDataManager()
    {
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        return $this->cookieMetadataFactory;
    }
    /**
     * Format JSON response.
     *
     * @param \Magento\Framework\Phrase $phrase
     * @return \Magento\Framework\Controller\Result\Json
     */
    private function returnJsonError(\Magento\Framework\Phrase $phrase): \Magento\Framework\Controller\Result\Json
    {
        $resultJson = $this->resultJsonFactory->create();
        $result["error"] = true;
        $result["reload"] = true;
        $result["msg"] = $phrase;
        return $resultJson->setData($result);
    }
}
