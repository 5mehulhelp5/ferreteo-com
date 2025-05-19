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
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Ui\Component\MassAction\Filter;

class Massenable extends \Magento\Backend\App\Action
{
    private $question;

    private $helper;

    /**
     *
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param Action\Context $context
     * @param \Webkul\Mpqa\Model\QuestionFactory $questionFactory
     * @param \Webkul\Mpqa\Helper\Data $helper
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        Action\Context $context,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Webkul\Mpqa\Helper\Data $helper
    ) {
        $this->_filter = $filter;
        $this->question = $questionFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Mpqa::index');
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $questionCollection = $this->question->create();
            $collection = $this->_filter->getCollection($questionCollection->getCollection());
            foreach ($collection as $ques) {
                $this->changeStatus($ques, 1);
            }
            $this->messageManager->addSuccess(__('Question(s) enabled successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Change Status
     */
    public function changeStatus($ques, $val)
    {
        $ques->setId($ques->getQuestionId())
                    ->setStatus($val)
                    ->save();
    }
}
