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

  /**
   * @var \RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi\CollectionFactory
   */
  protected $_noshiCollectionFactory;

  /**
   * NoshiForm constructor.
   * @param \Magento\Framework\View\Element\Template\Context $context
   * @param CheckoutSession $checkoutSession
   * @param \RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi\CollectionFactory $collectionFactory
   * @param Logger $logger
   */
  public function __construct(
      \Magento\Framework\View\Element\Template\Context $context,
      CheckoutSession $checkoutSession,
      \RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi\CollectionFactory $collectionFactory,
      Logger $logger
  ) {
    parent::__construct($context);

    $this->_noshiCollectionFactory = $collectionFactory;

    $this->logger = $logger;
    $this->checkoutSession = $checkoutSession;
  }

  public function getPurposeOption() {

    return [
        'celebration' => __('Celebration'),
        'buddhist memorial' => __('Buddhist Memorial Service')
    ];
  }

  public function getCongratulationsOption() {

    return [
        '' => __('Please Select'),
        'works' => __('Works'),
        'no works' => __('No Works'),
        'ribbon' => __('Ribbon Packing')
    ];
  }

  public function getNoshiCelebrationOption() {

    return [
        'greetings' => __('Greetings'),
        'marriage' => __('Marriage'),
        'baby gift' => __('Baby Gift'),
        'disease recovery' => __('Disease recovery'),
        'celebration' => __('Celebration')
    ];
  }

  public function getBabygiftInscriptionOption() {

    return [
        1 => __('Holidays'),
        2 => __('Family Celebration')
    ];

  }

  /**
   * Getting Noshi items collection
   *
   * @return mixed
   */
  public function getNoshiCollection()
  {
    if(!$this->hasData('noshi_items')) {
      $noshiData = $this->_noshiCollectionFactory
          ->create()
          ->addFilter('visibility', 1);
      $this->setData('noshi_items', $noshiData);
    }

    return $this->getData('noshi_items');
  }

  /**
   * To options array
   *
   * @return array
   */
  public function toOptionArray()
  {
    $data = [];
    $data[] = ['value' => '', 'label' => ''];
    
    foreach ($this->getNoshiCollection() as $item) {
         $data[] = ['value' => $item->getCode(), 'label' => $item->getName()];
    }
    
    return $data;
  }
}