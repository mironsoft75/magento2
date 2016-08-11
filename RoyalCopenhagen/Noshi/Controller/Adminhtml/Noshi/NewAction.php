<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi;

class NewAction extends \RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
{
    /**
     * Create new Noshi item
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->_initModel();
        $resultPage = $this->initResultPage();
        $resultPage->getConfig()->getTitle()->prepend(__('New Noshi item'));
        return $resultPage;
    }
}