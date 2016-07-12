<?php
namespace RoyalCopenhagen\Namecustom\Block;

use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;
/**
* ProductListing block
*/
class NameCustomisation extends \Magento\Framework\View\Element\Template
{
    protected $checkoutSession;
    protected $logger;

    public function __construct(
        CheckoutSession $checkoutSession,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
      }

    public function showTeaCheckoutForm() {
      //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      //$localeDate = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
      $_show_tea = $this->checkoutSession->getData('_show_tea_checkout_form');
      $this->logger->info('Show Tea: '.var_export($_show_tea, true));

      return $_show_tea;
    }

    public function showYearPlateCheckoutForm() {

      $_show_yearplate = $this->checkoutSession->getData('_show_yearplate_checkout_form');
      $this->logger->info('Show Year Plate: '.var_export($_show_yearplate, true));
      return $_show_yearplate;
    }
}