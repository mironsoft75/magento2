<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;

class GetQuote implements ObserverInterface {

  public function execute(Observer $observer) {
    $event = $observer->getEvent();
    $quote = $event->getQuote();
    $this->_objectManager->get('Psr\Log\LoggerInterface')->debug('something');
  }
}