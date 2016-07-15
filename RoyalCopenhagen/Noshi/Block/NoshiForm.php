<?php
namespace RoyalCopenhagen\Noshi\Block;

use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;

/**
* NoshiForm block
*/
class NoshiForm extends \Magento\Framework\View\Element\Template
{

  protected $checkoutSession;
  protected $logger;
  protected $purpose_option;

  public function __construct( \Magento\Framework\View\Element\Template\Context $context, CheckoutSession $checkoutSession, Logger $logger ) {
    parent::__construct($context);
    $this->logger = $logger;
    $this->checkoutSession = $checkoutSession;
    $this->purpose_option = array(1 => __('Celebration'), 2 => __('Buddhist Memorial Service'));
  }

  public function getCongratsCondolenceOption() {
    return $this->purpose_option;

  }


}