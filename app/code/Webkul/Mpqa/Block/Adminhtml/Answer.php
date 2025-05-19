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
namespace Webkul\Mpqa\Block\Adminhtml;

use Magento\Catalog\Helper\Image;

class Answer extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;

    protected $_question;

    protected $_productloader;

    protected $_answer;

    protected $_imagehelper;
    /**
     * Constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\Customer        $customer
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Webkul\Mpqa\Model\QuestionFactory $questionFactory,
        \Webkul\Mpqa\Model\MpqaanswerFactory $answerFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->_formFactory = $formFactory;
        $this->_customer = $customer;
        $this->_question=$questionFactory;
        $this->_answer=$answerFactory;
        $this->_imagehelper = $imageHelper;
        $this->_productloader=$_productloader;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * Initialize edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Webkul_Mpqa';
        $this->_controller = 'adminhtml';
        parent::_construct();

        $this->buttonList->remove('delete');
        $this->buttonList->remove('save');
    }

    /**
     * get parameters
     * @return array
     */
    public function getParams()
    {
        $data = $this->getRequest()->getParams();
        return $data;
    }

    /**
     * get Question
     * @return object
     */
    public function getQuestion()
    {
        $data = $this->getParams();
        if ($data['id']) {
            $collection = $this->_question
                ->create()->load($data['id']);

            return $collection;
        }
    }

    public function getProduct()
    {

        if ($this->getQuestion()->getProductId()) {
            return $this->_productloader->create()->load($this->getQuestion()->getProductId());
        }
    }

    public function getAnswers()
    {
        $collection = $this->_answer->create()->getCollection()
                    ->addFieldToFilter('question_id', $this->getRequest()->getParam('id'))
                    ->setOrder('answer_id', "DESC");
        return $collection;
    }

    public function imageHelperObj()
    {
        return $this->_imagehelper;
    }

    /**
     * Get isSecure Request
     */
    public function isSecure()
    {
        return $this->getRequest()->isSecure();
    }

    /**
     * Get Json Helper
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }
}
