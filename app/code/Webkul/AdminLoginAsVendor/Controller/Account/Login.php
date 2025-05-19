<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AdminLoginAsVendor
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AdminLoginAsVendor\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Session\SessionManager;
use Magento\Security\Model\ResourceModel\AdminSessionInfo\CollectionFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Psr\Log\LoggerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Helper\Data as BackendHelper;

/**
 * Webkul AdminLoginAsVendor Account Login Controller.
 */
class Login extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var CollectionFactory
     */
    public $sessionCollection;

    /**
     * @var RemoteAddress
     */
    public $remoteAddress;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var BackendHelper
     */
    private $backendHelper;

    /**
     * @param Context                   $context
     * @param PageFactory               $resultPageFactory
     * @param ForwardFactory            $resultForwardFactory
     * @param SessionFactory            $customerSession
     * @param CustomerFactory           $customer
     * @param SessionManager            $sessionManager
     * @param CollectionFactory         $sessionCollection
     * @param RemoteAddress             $remoteAddress
     * @param EncryptorInterface        $encryptor
     * @param LoggerInterface           $logger
     * @param JsonHelper                $jsonHelper
     * @param BackendHelper             $backendHelper
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        SessionFactory $customerSession,
        CustomerFactory $customer,
        SessionManager $sessionManager,
        CollectionFactory $sessionCollection,
        RemoteAddress $remoteAddress,
        EncryptorInterface $encryptor,
        LoggerInterface $logger,
        JsonHelper $jsonHelper,
        BackendHelper $backendHelper
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->customerSession = $customerSession;
        $this->customer = $customer;
        $this->sessionManager = $sessionManager;
        $this->sessionCollection = $sessionCollection;
        $this->remoteAddress = $remoteAddress;
        $this->encryptor = $encryptor;
        $this->logger = $logger;
        $this->jsonHelper = $jsonHelper;
        $this->backendHelper = $backendHelper;
        parent::__construct($context);
    }

    /**
     * AdminLoginAsVendor action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $this->getResponse()->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        if ($cif = $this->getRequest()->getParam('cif')) {
            $cif = str_replace(" ", "+", $cif);
            try {
                $userInfoData = $this->jsonHelper->jsonDecode($this->encryptor->decrypt($cif));
                if ($this->isAdminloggedIn($userInfoData)) {
                    if (!empty($userInfoData['seller_id'])) {
                        $sellerId = $userInfoData['seller_id'];
                    } else {
                        $sellerId = $userInfoData['customer_id'];
                    }
                    $loginFlag = 0;
                    // Check If any customer account already login
                    $customerSession = $this->customerSession->create();
                    if ($customerSession->isLoggedIn() && !(($customerSession->getCustomerId() != $sellerId))) {
                        $loginFlag = 1;
                    }
                    if (!$loginFlag) {
                        if ($this->customer->create()->load($sellerId)->getId()) {
                            $this->customerSession->create()->setId($sellerId);
                            $this->customerSession->create()->loginById($sellerId);
                            $customerSessionData = $this->sessionManager->getData();
                            $visitorData = [];
                            $visitorData['customer_id'] = $sellerId;
                            $this->sessionManager->setVisitorData($visitorData);
                        } else {
                            $resultForward = $this->resultForwardFactory->create();
                            $resultForward->forward('noroute');
                            return $resultForward;
                        }
                    }
                    if (!empty($userInfoData['seller_id'])) {
                        return $this->resultRedirectFactory->create()->setPath(
                            'marketplace/account/dashboard',
                            [
                                '_secure' => $this->getRequest()->isSecure()
                            ]
                        );
                    } else {
                        return $this->resultRedirectFactory->create()->setPath(
                            'customer/account/',
                            [
                                '_secure' => $this->getRequest()->isSecure()
                            ]
                        );
                    }
                } else {
                    $adminUrl = $this->backendHelper->getUrl('admin/auth/logout');
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setUrl($adminUrl);

                    return $resultRedirect;
                }
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $resultForward = $this->resultForwardFactory->create();
                $resultForward->forward('noroute');
                return $resultForward;
            }
        } else {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');
            return $resultForward;
        }
    }

    /**
     * check if admin logged in or not
     * @return boolean
     */
    protected function isAdminloggedIn($userInfoData)
    {
        if (!empty($userInfoData['asid'])) {
            $adminSessionId = '';
            $collection = $this->sessionCollection->create()
            ->addFieldToSelect(
                '*'
            )
            ->addFieldToFilter('ip', $this->remoteAddress->getRemoteAddress())
            ->setOrder('id', 'DESC')
            ->setPageSize(1)
            ->setCurPage(1);

            foreach ($collection as $key => $value) {
                if ($value->getStatus()) {
                    $adminSessionId = $value->getSessionId();
                }
            }

            if ($adminSessionId && ($adminSessionId == $userInfoData['asid'])) {
                return true;
            } else {
                $adminSessionId = '';
                $collection = $this->sessionCollection->create()
                ->addFieldToSelect(
                    '*'
                )
                ->addFieldToFilter('ip', $this->remoteAddress->getRemoteAddress())
                ->setOrder('id', 'DESC')
                ->setPageSize(1)
                ->setCurPage(2);
                foreach ($collection as $key => $value) {
                    if ($value->getStatus()) {
                        $adminSessionId = $value->getSessionId();
                    }
                }
                if ($adminSessionId && ($adminSessionId == $userInfoData['asid'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
