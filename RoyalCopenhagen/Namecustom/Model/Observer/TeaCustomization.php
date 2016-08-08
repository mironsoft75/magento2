<?php

namespace RoyalCopenhagen\Namecustom\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Psr\Log\LoggerInterface as Logger;

class TeaCustomization implements ObserverInterface {
  protected $_logger;
  protected $_checkoutSession;
  protected $_quote;
  protected $cart;
  protected $_plate_name_line_one;
  protected $_plate_name_line_two;
  protected $_tea_name_line_one;
  protected $_tea_name_line_two;
  protected $_buyRequest;
  protected $_product;
  protected $_productCollectionFactory;
  public function __construct(
    CheckoutSession $_checkoutSession, 
    Logger $logger,
    \Magento\Catalog\Model\Product $product,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    )
  {
    $this->_productCollectionFactory = $productCollectionFactory;
    $this->_product = $product;
    $this->_logger = $logger;
    $this->_checkoutSession = $_checkoutSession;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {

    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $helper = $objectManager->get('Magento\Catalog\Helper\Product\Configuration');
    $this->_buyRequest = new \Magento\Framework\DataObject;
    $request = $observer->getEvent()->getRequest();
    $params = $request->getParams();
    $cart = $observer->getEvent()->getCart();
        if (($params['tea_name_line'][0] != '')|| ($params['tea_name_line'][1] != '')){

          //insert tea name into session
          if ($params['tea_name_line'][0] != ''){
            $_rc_tea_name_line1 = $params['tea_name_line'][0];
          }else{
            $_rc_tea_name_line1 = '';
          }
          if ($params['tea_name_line'][1] != ''){
            $_rc_tea_name_line2 = $params['tea_name_line'][1];
          }else{
            $_rc_tea_name_line2 = '';
          }
          $this->_checkoutSession->setData('_rc_tea_name_line1', $_rc_tea_name_line1);
          $this->_checkoutSession->setData('_rc_tea_name_line2', $_rc_tea_name_line2);

          $list = $cart->getItems();
          foreach ($list as $_item){
              $productIds[] = $_item->getSku();
          }

          if (count($productIds) > 0){
              $collection = $this->_productCollectionFactory->create();
              $collection->addAttributeToSelect('*')
              ->addAttributeToFilter(
              'sku', array('in' => $productIds)
              );

              foreach ($collection as $product) {
                  $productList[$product->getId()] = $product;
              }

              $list = $cart->getItems();
              foreach ($list as $_item){
                  $productObject = $productList[$_item->getProductId()];
                  if (($productObject->getRcNameCustomization()) && ($productObject->getRcCustomizedProductType() == 92 )){
                           $buyRequest = $_item->getOptionByCode('info_buyRequest');
                           $buyRequestArr = unserialize($buyRequest->getValue());
                           $buyRequestArr['teaNameCustomization'] = $params['tea_name_line'];
                           $buyRequest->setValue(serialize($buyRequestArr))->save();
                 };
              }
          }
        }

  }
}
