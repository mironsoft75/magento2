<?php

namespace RoyalCopenhagen\Noshi\Model\Multishipping;

use Magento\Sales\Model\Order\Pdf\AbstractPdf;

class PdfNoshi extends AbstractPdf
{

    const NOSHI_SIZE_PDF = '1100:850:';

    /**
     * PdfNoshi constructor.
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Sales\Model\Order\Pdf\Config $pdfConfig
     * @param \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory
     * @param \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param array $data = []
     */
    public function __construct(
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Sales\Model\Order\Pdf\Config $pdfConfig,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        array $data = []
    )
    {
        parent::__construct(
            $paymentData, 
            $string, 
            $scopeConfig, 
            $filesystem, 
            $pdfConfig, 
            $pdfTotalFactory, 
            $pdfItemsFactory, 
            $localeDate, 
            $inlineTranslation, 
            $addressRenderer, 
            $data
        );
    }

    /**
     * Return PDF document
     *
     * @param null $path
     * @param null $name
     * @return \Zend_Pdf
     */
    public function getPdf($path = null, $name = null)
    {
        $this->_beforeGetPdf();
        
        $pdf = new \Zend_Pdf();

        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        $page = $this->newPage();
        /* Add image */
        $this->insertNoshi($page, $path);
        if(!empty($name)) {
            $this->insertNoshiName($page, $name);
        }

        $this->_afterGetPdf();
        
        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return \Zend_Pdf_Page
     */
    public function newPage(array $settings = [])
    {
        $pageSize = !empty($settings['page_size']) ? $settings['page_size'] : self::NOSHI_SIZE_PDF;
        $page = $this->_getPdf()->newPage($pageSize);
        $this->_getPdf()->pages[] = $page;
        $this->y = 1100;

        return $page;
    }

    /**
     * Insert logo to pdf page
     *
     * @param \Zend_Pdf_Page &$page
     * @param string $imagePath
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function insertNoshi(&$page, $imagePath = null)
    {
        $this->y = $this->y ? $this->y: 810;
        
        if($imagePath) {
            
            if($this->_mediaDirectory->isFile($imagePath)) {
                $image = \Zend_Pdf_Image::imageWithPath($this->_mediaDirectory->getAbsolutePath($imagePath));
                $top = 810;

                $width = $image->getPixelWidth();
                $height = $image->getPixelHeight();
                $y1 = $top - $height;
                $y2 = $top;
                $x1 = 5;
                $x2 = $x1 + $width;
                //coordinates after transformation are rounded by Zend
                $page->drawImage($image, $x1, $y1, $x2, $y2);
                $this->y = $y1;
                
            }
            
        }
    }
    /**
     * Insert Noshi name
     *
     * @param \Zend_Pdf_Page &$page
     * @param array $nameData
     * @return void
     */
    public function insertNoshiName(&$page, $nameData)
    {
        $this->y = 450;
        $font = $this->_setFontBold($page, 45);
        $top = 380;
        /**@TODO trying to hard core the text Align**/
        foreach ($nameData as $name) {

            if($name['noshiName1']) {
                foreach ($this->string->split($name['noshiName1'], 1, true, true) as $_value) {
                    $page->drawText(
                        trim(strip_tags($_value)),
                        $this->getAlignRight($_value, 480, 40, $font, 10),
                        $top,
                        'UTF-8'
                    );
                    $top -=40;
                }
            }
            //Reset top
            $top = 380;
            if($name['noshiName2']) {
                foreach ($this->string->split($name['noshiName2'], 1, true, true) as $_value) {
                    $page->drawText(
                        trim(strip_tags($_value)),
                        $this->getAlignRight($_value, 545, 40, $font, 10),
                        $top,
                        'UTF-8'
                    );
                    $top -=40;
                }
            }
        }

        $this->y = $this->y > $top ? $top : $this->y;
    }

}