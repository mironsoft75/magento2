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
    protected $cart;
    protected $product;
    public function __construct( 
      \Magento\Framework\View\Element\Template\Context $context, 
      CheckoutSession $checkoutSession, Logger $logger,
      \Magento\Checkout\Model\Cart $cart,
      \Magento\Catalog\Model\Product $product
      ) {
        $this->cart = $cart;
        $this->product = $product;
        parent::__construct($context);
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
      }

    //check if year plate exist in the cart
    public function showTeaCheckoutForm() {
        $this->_rc_tea_name_line1 = $this->checkoutSession->getData('_rc_tea_name_line1');
        $this->_rc_tea_name_line2 = $this->checkoutSession->getData('_rc_tea_name_line2');
        if (!empty($this->_rc_tea_name_line1) || !empty($this->_rc_tea_name_line2)){
          return false;
        }

        $orderItems = $this->cart->getItems();
        foreach ($orderItems as $_item){
          $product = $this->product->load($_item->getProductId());
          $is_name_customisable = $product->getRcNameCustomization();
          if(($is_name_customisable == true) && ($product->getRcCustomizedProductType() == 92 )) {
            return true;
          }
        }
      return false;
    }

    //check if tea product exist in the cart
    public function showYearPlateCheckoutForm() {
        $this->_rc_plate_name_line1 = $this->checkoutSession->getData('_rc_plate_name_line1');
        $this->_rc_plate_name_line2 = $this->checkoutSession->getData('_rc_plate_name_line2');
        if (!empty($this->_rc_plate_name_line1) || !empty($this->_rc_plate_name_line2)){
          return false;
        }

        $orderItems = $this->cart->getItems();
        foreach ($orderItems as $_item){
          $product = $this->product->load($_item->getProductId());
          $is_name_customisable = $product->getRcNameCustomization();
          if(($is_name_customisable == true) && ($product->getRcCustomizedProductType() == 91 )) {
            return true;
          }
        }
      return false;
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