<?php
namespace RoyalCopenhagen\Catalog\Block;

/**
* ProductListing block
*/
class ProductListing
    extends \Magento\Framework\View\Element\Template
{
    public function checkProductNew ( \Magento\Catalog\Model\Product $product) {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

      $newsFromDate = $product->getNewsFromDate();
      $newsToDate   = $product->getNewsToDate();
      $localeDate = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
      $dateTime = $objectManager->get('\Magento\Framework\Stdlib\DateTime');

      if (! $dateTime->isEmptyDate($newsFromDate)) {
        return $localeDate->isScopeDateInInterval(
               $product->getStoreId(),
               $newsFromDate,
               $newsToDate
           );

      } else {
        return false;
      }
  }
}