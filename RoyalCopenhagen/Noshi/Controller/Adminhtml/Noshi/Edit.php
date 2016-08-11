<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi;

class Edit extends \RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
{
    /**
     * Edit Noshi item
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $model = $this->_initModel();
        $resultPage = $this->initResultPage();
        $formData = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData();
        if ($formData) {
            $model->addData($formData);
        }
        $resultPage->getConfig()->getTitle()->prepend(__('%1', $model->getName()));
        return $resultPage;
    }
}