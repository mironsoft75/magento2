<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
{
    /**
     * Delete current Noshi item
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $noshi = $this->_objectManager->create('RoyalCopenhagen\Noshi\Model\Noshi');
        $noshi->load($this->getRequest()->getParam('entity_id', false));
        if ($noshi->getId()) {
            try {
                $noshi->delete();
                $this->messageManager->addSuccess(__('You deleted the Noshi item.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('royal_noshi/noshi/index');
                return $resultRedirect;
            }
        }
        $resultRedirect->setPath('royal_noshi/noshi/index');
        return $resultRedirect;
    }
}