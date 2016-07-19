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
  protected $congratulations_option;
  protected $noshi_celebration_option;
  protected $noshi_celebration_marriage_inscription_option;
  protected $noshi_celebration_celebration_inscription_option;
  protected $noshi_celebration_babygift_inscription_option;


  public function __construct( \Magento\Framework\View\Element\Template\Context $context, CheckoutSession $checkoutSession, Logger $logger ) {
    parent::__construct($context);
    $this->logger = $logger;
    $this->checkoutSession = $checkoutSession;
    $this->purpose_option = array('celebration' => __('Celebration'), 'buddhist memorial' => __('Buddhist Memorial Service'));
    $this->congratulations_option = array('' => __('Please Select'), 'works' => __('Works'), 'no works' => __('No Works'), 'ribbon' => __('Ribbon Packing'));
    $this->noshi_celebration_option = array('greetings' => __('Greetings'), 'marriage' => __('Marriage'), 'baby gift' => __('Baby Gift'), 'disease recovery' => __('Disease recovery'), 'celebration' => __('Celebration'));
    $this->noshi_celebration_babygift_inscription_option = array(1 => __('Holidays'), 2 => __('Family Celebration'));

  }

  public function getPurposeOption() {
    return $this->purpose_option;
  }

  public function getCongratulationsOption() {
    return $this->congratulations_option;
  }

  public function getNoshiCelebrationOption() {
    return $this->noshi_celebration_option;
  }

  public function getBabygiftInscriptionOption() {
    return $this->noshi_celebration_babygift_inscription_option;
  }


}