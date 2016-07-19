<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;

class AfterSubmit implements ObserverInterface {
  protected $_logger;
  protected $_checkoutSession;
  protected $_quote;
  protected $_cart;
  protected $_plate_name_line_one;
  protected $_plate_name_line_two;
  protected $_tea_name_line_one;
  protected $_tea_name_line_two;
  protected $_buyRequest;

  public function __construct( CheckoutSession $_checkoutSession, Logger $logger)
  {
    $this->_logger = $logger;
    $this->_checkoutSession = $_checkoutSession;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $helper = $objectManager->get('Magento\Catalog\Helper\Product\Configuration');
    $this->_buyRequest = new \Magento\Framework\DataObject;

    $request = $observer->getEvent()->getRequest();

    $params = $request->getParams();

   try {
      if (isset($params['plate_name_line1'])) {
        $this->_plate_name_line_one = $params['plate_name_line1'];

        $this->_checkoutSession->setData('_rc_plate_name_line1', $this->_plate_name_line_one);
        // Turn off _show_yearplate_checkout_form
        $this->_checkoutSession->setData('_show_yearplate_checkout_form', false);

      }

      if (isset($params['plate_name_line2'])) {
        $this->_plate_name_line_two = $params['plate_name_line2'];

        $this->_checkoutSession->setData('_rc_plate_name_line2', $this->_plate_name_line_two);

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

    $this->_cart = $observer->getEvent()->getCart();
    $this->_logger->info('NameCustomisation: '.count($this->_cart->getItems()));

    foreach ($this->_cart->getItems() as $item) {
      $quoteItem =$helper->getCustomOptions($item);
      $itemId = $item->getId();
      $product = $item->getProduct();
      $is_name_customisable = $product->getRcNameCustomization();


      if(!empty ($this->_plate_name_line_one)) {
        $buyRequestParam['name_customisation']['plate_name_line_one'] = $this->_plate_name_line_one;
         $additionalOptions['plate_name_line_one'] = array(
            'label' => __('Name Plate One'),
            'value' => $this->_plate_name_line_one,
        );
      }

      if(!empty ($this->_plate_name_line_two)) {
        $buyRequestParam['name_customisation']['plate_name_line_two'] = $this->_plate_name_line_two;
        $additionalOptions['plate_name_line_two'] = array(
            'label' => __('Name Plate One'),
            'value' => $this->_plate_name_line_two,
        );
      }

      if(! empty($additionalOptions)) {
        if($is_name_customisable) {
          $product->addCustomOption('additional_options', serialize($additionalOptions));
        }

      }

      //$buyRequest->setValue(serialize($buyRequestArr))->save();
      //$this->_cart->updateItem($itemId, $buyRequest, null);
      //$item->save();
      //$this->_cart->save();

      $this->_logger->info('NameCustomisation: $product :: '.get_class($product));

    }


  }
}
