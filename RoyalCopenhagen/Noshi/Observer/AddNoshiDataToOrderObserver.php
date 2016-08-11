<?php

namespace RoyalCopenhagen\Noshi\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddNoshiDataToOrderObserver implements ObserverInterface
{
    /**
     * @var \RoyalCopenhagen\Noshi\Model\Noshi
     */
    protected $_noshiModel;

    /**
     * AddNoshiDataToOrderObserver constructor.
     * @param \RoyalCopenhagen\Noshi\Model\Noshi $noshiModel
     */
    public function __construct(
        \RoyalCopenhagen\Noshi\Model\Noshi $noshiModel
    )
    {
        $this->_noshiModel = $noshiModel;
    }

    /**
     * Set Noshi data to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        $noshiCode = $quote->getRoyalNoshiCode();
        if(!$noshiCode) {
            return $this;
        }
        //Set Noshi data to order
        $noshiId = $this->_noshiModel->checkNoshiCode($noshiCode);
        if($noshiId) {
            $noshiItem = $this->_noshiModel->load($noshiId);
            $order = $observer->getEvent()->getOrder();
            $order->setData('royal_noshi_code', $noshiItem->getCode());
            $order->setData('royal_noshi_name', $noshiItem->getName());
            $order->setData('royal_noshi_description', $noshiItem->getDescription());
        }
        return $this;
    }
}