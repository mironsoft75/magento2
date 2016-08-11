<?php

namespace RoyalCopenhagen\Noshi\Controller\Multishipping;

use Magento\Framework\App\Action\Context;
use RoyalCopenhagen\Noshi\Model\Multishipping\PdfNoshi;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Filesystem\DirectoryList;

class PdfView extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PdfNoshi
     */
    protected $_pdfNoshi;

    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * @var DateTime
     */
    protected $_dateTime;

    /**
     * PdfView constructor.
     * @param PdfNoshi $pdfNoshi
     * @param FileFactory $fileFactory
     * @param DateTime $dateTime
     * @param Context $context
     */
    public function __construct(
        PdfNoshi $pdfNoshi,
        FileFactory $fileFactory,
        DateTime $dateTime,
        Context $context
    )
    {
        $this->_pdfNoshi = $pdfNoshi;
        $this->_dateTime = $dateTime;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * Execute to generate Pdf file
     */
    public function execute()
    {
        $noshiCode = $this->getRequest()->getParam('noshi_code');
        $model = $this->_objectManager->create('RoyalCopenhagen\Noshi\Model\Noshi');
        $noshiId = $model->checkVisibleNoshiCode($noshiCode);
        if($noshiId) {
            $model->load($noshiId);
        } else {
            return null;
        }
        //Noshi Name
        $noshiName1 = $this->getRequest()->getParam('name1');
        $noshiName2 = $this->getRequest()->getParam('name2');
        $noshiName = [];
        if($noshiName1 && $noshiName2) {
            $noshiName[] = [
                'noshiName1' => $noshiName1,
                'noshiName2' => $noshiName2
            ];
        }
        $imagePath = 'noshi/' . $model->getImage();
        
        return $this->_fileFactory->create(
            sprintf('noshi_preview%s.pdf', $this->_dateTime->date('Y-m-d_H-i-s')),
            $this->_pdfNoshi->getPdf($imagePath, $noshiName)->render(),
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }
}