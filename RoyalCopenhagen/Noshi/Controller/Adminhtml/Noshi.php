<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml;

use Magento\Framework\Controller\ResultFactory;

abstract class Noshi extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context, 
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init active menu
     *
     * @return \Magento\Backend\Model\View\Result\Page
     * @codeCoverageIgnore
     */
    protected function initResultPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('RoyalCopenhagen_Noshi::noshi');
        $resultPage->getConfig()->getTitle()->prepend(__('Noshi Item'));
        return $resultPage;
    }

    /**
     * Init model
     *
     * @param string $requestParam
     * @return \RoyalCopenhagen\Noshi\Model\Noshi
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initModel($requestParam = 'entity_id')
    {
        $model = $this->_coreRegistry->registry('noshi_item');
        if ($model) {
            return $model;
        }
        $model = $this->_objectManager->create('RoyalCopenhagen\Noshi\Model\Noshi');
        $model->setStoreId($this->getRequest()->getParam('store', 0));

        $noshiId = $this->getRequest()->getParam($requestParam);
        if ($noshiId) {
            $model->load($noshiId);
            if (!$model->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Please request the correct Noshi.')
                );
            }
        }
        $this->_coreRegistry->register('noshi_item', $model);

        return $model;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RoyalCopenhagen_Noshi::noshi');
    }
    /**
     * Prepare Noshi Raw data
     *
     * @param array $noshiRawData
     * @return array
     */
    protected function _prepareNoshiRawData($noshiRawData)
    {
        if (isset($noshiRawData['tmp_image'])) {
            $noshiRawData['tmp_image'] = basename($noshiRawData['tmp_image']);
        }
        if (isset($noshiRawData['image_name']['value'])) {
            $noshiRawData['image_name']['value'] = basename($noshiRawData['image_name']['value']);
        }
        return $noshiRawData;
    }
}