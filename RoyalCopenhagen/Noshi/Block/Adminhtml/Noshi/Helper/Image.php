<?php

namespace RoyalCopenhagen\Noshi\Block\Adminhtml\Noshi\Helper;

class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * Get gift wrapping image url
     *
     * @return string|boolean
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = $this->getForm()->getDataObject()->getImageUrl();
        }
        return $url;
    }
}