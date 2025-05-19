<?php
namespace Magecomp\Cityandregionmanager\Controller\Adminhtml\Zip;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magecomp\Cityandregionmanager\Model\Zip;

class Save extends Action
{
    protected $_modelSession;

    protected $_model;

    public function __construct(
        Action\Context $context,
        Session $session,
        Zip $model
    )
    {
        $this->_modelSession = $session;
        $this->_model = $model;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_model;

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $model->load($id);
            } else {
                unset($data['entity_id']);
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'magecomp_cityandregionmanager_zip_prepare_save',
                ['data' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('Zipcode is saved successfully!'));
                $this->_modelSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving a zipcode.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
