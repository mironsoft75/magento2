<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi;

use Magento\Framework\Controller\ResultFactory;
/**
 * Class Save
 * @package RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
 */
class Save extends \RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
{
    /**
     * Save Noshi item
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $noshiRawData = $this->_prepareNoshiRawData($this->getRequest()->getPost('noshi_item'));
        if ($noshiRawData) {
            try {
                $model = $this->_initModel();

                //@TODO need to check in Noshi Model
                if($model->getCode() != $noshiRawData['code']) {
                    $itemId = $model->checkNoshiCode($noshiRawData['code']);
                    if($itemId) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('The Noshi code is exist. Please check again.')
                        );
                    }
                }
                $model->addData($noshiRawData);

                $data = new \Magento\Framework\DataObject($noshiRawData);
                if ($data->getData('image_name/delete')) {
                    $model->setImage('');
                    // Delete temporary image if exists
                    $model->unsTmpImage();
                } else {
                    try {
                        $model->attachUploadedImage('image_name');
                    } catch (\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('You have not uploaded the image.')
                        );
                    }
                }

                $model->save();
                $this->messageManager->addSuccess(__('You saved the Noshi item.'));

                $redirectBack = $this->getRequest()->getParam('back', false);
                if ($redirectBack) {
                    $resultRedirect->setPath(
                        'royal_noshi/*/edit',
                        ['entity_id' => $model->getId()]
                    );
                    return $resultRedirect;
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('royal_noshi/*/edit', ['entity_id' => $model->getId()]);
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            }
        }
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }

}