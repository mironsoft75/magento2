<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;

class GetQuoteUpdate implements ObserverInterface {

  protected $logger;
  protected $_checkoutSession;


  public function __construct(
    CheckoutSession $_checkoutSession,
    Logger $logger
  )
  {
    $this->logger = $logger;
    $this->_checkoutSession = $_checkoutSession;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    /** Reset all the sessions first */
    /** Reset all the sessions first */
    $this->_checkoutSession->setData('_rc_plate_name_line1', false);
    $this->_checkoutSession->setData('_rc_plate_name_line2', false);
    $this->_checkoutSession->setData('_rc_tea_name_line1', false);
    $this->_checkoutSession->setData('_rc_tea_name_line2', false);

    $this->_checkoutSession->setData('_show_tea_checkout_form', false);
    $this->_checkoutSession->setData('_show_yearplate_checkout_form', false);

    $quote =  $this->_checkoutSession->getQuote();

    foreach ($quote->getAllVisibleItems() as $item){
      $product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
      $is_name_customisable = $product->getRcNameCustomization();

      if($is_name_customisable == true) {
        $customisable_type = $product->getRcCustomizedProductType();

        if( $customisable_type == 92 ) { //tea_type
           $this->_checkoutSession->setData('_show_tea_checkout_form', true);


         }
         if( $customisable_type == 91 ) { // year_plate
           $this->_checkoutSession->setData('_show_yearplate_checkout_form', true);

        }

      }

    }

  }
}
