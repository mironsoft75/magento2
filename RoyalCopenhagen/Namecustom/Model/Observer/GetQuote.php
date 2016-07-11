<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface as Logger;

class GetQuote implements ObserverInterface {

  protected $logger;

  public function __construct(
    \Psr\Log\LoggerInterface $logger
  )
  {
    $this->logger = $logger;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $event = $observer->getEvent();
    /** @var Product $product */
    $product = $event->getData('product');

    $this->logger->debug('something');
  }
}