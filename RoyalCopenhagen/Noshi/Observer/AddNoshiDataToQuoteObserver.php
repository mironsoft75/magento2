<?php

namespace RoyalCopenhagen\Noshi\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddNoshiDataToQuoteObserver implements ObserverInterface
{
    /**
     * @var \RoyalCopenhagen\Noshi\Model\Noshi
     */
    protected $_noshiModel;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * AddNoshiDataToOrderObserver constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \RoyalCopenhagen\Noshi\Model\Noshi $noshiModel
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \RoyalCopenhagen\Noshi\Model\Noshi $noshiModel
    )
    {
        $this->_checkoutSession = $checkoutSession;
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
        $data = $observer->getRequest()->getParams();
        $quoteSession = $this->_checkoutSession->getQuote();
        //@TODO trying to hard now
        //Check required gift wrapping
        if(isset($data['required_gift_wrapping'])) {
            $quoteSession->setData('royal_required_gift_wrapping', $data['required_gift_wrapping']);
        } else {
            $quoteSession->setData('royal_required_gift_wrapping', '');
        }
        if(isset($data['purpose'])) {
            $quoteSession->setData('royal_purpose', $data['purpose']);
        } else {
            $quoteSession->setData('royal_purpose', '');
        }
        if(isset($data['type_gift_wrapping']) && $data['type_gift_wrapping'] == 'ribbon') {
            $quoteSession->setData('royal_ribbon', 'ribbon');
        } else {
            $quoteSession->setData('royal_ribbon', '');
        }
        //Assign Noshi data to quote
        //@TODO trying to hard code with Noshi data.
        if(isset($data['noshi']) && isset($data['noshi']['code'])) {
            $quoteSession->setData('royal_noshi_code', $data['noshi']['code']);
            $quoteSession->setData('royal_noshi_name', $data['noshi']['name']);
        } else {
            $quoteSession->setData('royal_noshi_code', '');
            $quoteSession->setData('royal_noshi_name', '');
        }

        if(isset($data['reciver_name1'])) {
            $quoteSession->setData('royal_noshi_receiver_name_1', $data['reciver_name1']);
        } else {
            $quoteSession->setData('royal_noshi_receiver_name_1', '');
        }
        if(isset($data['reciver_name2'])) {
            $quoteSession->setData('royal_noshi_receiver_name_2', $data['reciver_name2']);
        } else {
            $quoteSession->setData('royal_noshi_receiver_name_2', '');
        }
        if(isset($data['required_shopping_bag'])) {
            $quoteSession->setData('royal_required_shopping_bag', $data['required_shopping_bag']);
        } else {
            $quoteSession->setData('royal_required_shopping_bag', '');
        }
        if(isset($data['required_gift_wrapping'])) {
            $quoteSession->setData('royal_required_gift_wrapping', $data['required_gift_wrapping']);
        } else {
            $quoteSession->setData('required_gift_wrapping', '');
        }

        $this->_checkoutSession->getQuote()->save();

        return $this;
    }

}