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
    protected $_tea_name_line1;
    protected $_tea_name_line2;
    protected $_rc_plate_name_line1;
    protected $_rc_plate_name_line2;

    public function __construct( \Magento\Framework\View\Element\Template\Context $context, CheckoutSession $checkoutSession, Logger $logger ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
      }

    public function showTeaCheckoutForm() {
      $_show_tea = $this->checkoutSession->getData('_show_tea_checkout_form');

      $this->logger->info('NameCustomisation: Show Tea: '.var_export($_show_tea, true));

      return $_show_tea;
    }

    public function showYearPlateCheckoutForm() {
      $_show_yearplate = $this->checkoutSession->getData('_show_yearplate_checkout_form');

      $this->logger->info('NameCustomisation: Show Year Plate: '.var_export($_show_yearplate, true));

      return $_show_yearplate;
    }

    public function showSubmittedTeaName() {
      $this->_tea_name_line1 = $this->checkoutSession->getData('_rc_tea_name_line1');
      $this->_tea_name_line2 = $this->checkoutSession->getData('_rc_tea_name_line2');

      return !empty($this->_tea_name_line1) || !empty($this->_tea_name_line2);
    }

    public function showSubmittedNamePlate() {
      $this->_rc_plate_name_line1 = $this->checkoutSession->getData('_rc_plate_name_line1');
      $this->_rc_plate_name_line2 = $this->checkoutSession->getData('_rc_plate_name_line2');

      return !empty($this->_rc_plate_name_line1) || !empty($this->_rc_plate_name_line2);
    }

    public function getTeaNameOne() {
      return $this->_tea_name_line1;
    }

    public function getTeaNameTwo() {
      return $this->_tea_name_line2;
    }

    public function getNamePlateOne() {
      return $this->_rc_plate_name_line1;
    }

    public function getNamePlateTwo() {
      return $this->_rc_plate_name_line2;
    }

}