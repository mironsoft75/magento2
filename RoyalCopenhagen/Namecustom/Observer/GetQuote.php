<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;

protected $logger;

class GetQuote implements ObserverInterface {

  public function __construct (\Psr\Log\LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function execute(Observer $observer) {
    $event = $observer->getEvent();
    $quote = $event->getQuote();
    $this->logger->debug('something');
  }
}