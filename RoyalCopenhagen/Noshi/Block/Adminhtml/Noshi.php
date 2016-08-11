<?php

namespace RoyalCopenhagen\Noshi\Block\Adminhtml;

class Noshi extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_noshi';
        $this->_blockGroup = 'RoyalCopenhagen_Noshi';
        $this->_headerText = __('Noshi');
        parent::_construct();
    }
}