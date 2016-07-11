<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface as Logger;

class GetQuote implements ObserverInterface {

  protected $logger;
  public $_namecustomSession;

  public function __construct(
    \Magento\Catalog\Model\Session $_namecustomSession,
    \Psr\Log\LoggerInterface $logger
  )
  {
    $this->logger = $logger;
    $this->_namecustomSession = $_namecustomSession;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $event = $observer->getEvent();
    /** @var Product $product */
    $product = $event->getData('product');

    $this->logger->info(get_class($product));
  }
}