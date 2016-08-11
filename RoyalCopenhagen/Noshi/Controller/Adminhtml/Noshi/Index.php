<?php

namespace RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi;

class Index extends \RoyalCopenhagen\Noshi\Controller\Adminhtml\Noshi
{

    public function execute()
    {
        $resultPage = $this->initResultPage();
        return $resultPage;
    }
}