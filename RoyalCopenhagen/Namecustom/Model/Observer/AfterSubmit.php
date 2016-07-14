<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;

class AfterSubmit implements ObserverInterface {
  protected $logger;
  protected $_checkoutSession;

  public function __construct( CheckoutSession $_checkoutSession, Logger $logger)
  {
    $this->logger = $logger;
    $this->_checkoutSession = $_checkoutSession;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


    $request = $observer->getEvent()->getRequest();
    $params = $request->getParams();

    try {
      if (isset($params['plate_name_line1'])) {
        $this->_checkoutSession->setData('_rc_plate_name_line1', $params['plate_name_line1']);
        // Turn off _show_yearplate_checkout_form
        $this->_checkoutSession->setData('_show_yearplate_checkout_form', false);

      }

      if (isset($params['plate_name_line2'])) {
        $this->_checkoutSession->setData('_rc_plate_name_line2', $params['plate_name_line2']);
        // Turn off _show_yearplate_checkout_form
        $this->_checkoutSession->setData('_show_yearplate_checkout_form', false);

      }

      if (isset($params['tea_name_line1'])) {
        $this->_checkoutSession->setData('_rc_tea_name_line1', $params['tea_name_line1']);
        // Turn off _show_tea_checkout_form
        $this->_checkoutSession->setData('_show_tea_checkout_form', false);

      }

      if (isset($params['tea_name_line2'])) {
        $this->_checkoutSession->setData('_rc_tea_name_line2', $params['tea_name_line2']);
        // Turn off _show_tea_checkout_form
        $this->_checkoutSession->setData('_show_tea_checkout_form', false);

      }

    } catch (\Magento\Framework\Exception\LocalizedException $e) {
       $this->logger->info('multishipping_checkout_address_submit_complete :: '.$e->getMessage());
    }


  }
}
