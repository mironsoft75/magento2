<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;

class GetQuote implements ObserverInterface {

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $event = $observer->getEvent();
    /** @var Product $product */
    $product = $event->getData('product');

    $this->_objectManager->get('Psr\Log\LoggerInterface')->debug('something');
  }
}