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

class Deleteanswer extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    private $answer;

    /**
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $resultPage;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    private $helper;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Webkul\Mpqa\Helper\Data $helper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Webkul\Mpqa\Helper\Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->answer=$answerFactory;
        $this->_resultJsonFactory=$resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Mpqa::index');
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $model = $this->answer->create()->getCollection()
                ->addFieldToFilter('answer_id', $data['ans']);
        foreach ($model as $key) {
            $this->deleteAnswer($key);
        }
        $result = $this->_resultJsonFactory->create();
        $result->setData(['msg' => 'yes']);
        return $result;
    }

    /**
     * Delete answer
     */
    public function deleteAnswer($key)
    {
        $key->setId($key->getAnswerId())->delete();
    }
}
