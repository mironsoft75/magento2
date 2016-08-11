<?php

namespace RoyalCopenhagen\Noshi\Block\Adminhtml\Order;

/**
 * Class View
 * @package RoyalCopenhagen\Noshi\Block\Adminhtml\Order
 */
class View extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * View Noshi order constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * Retrieve Royal Required Gift Wrapping
     * 
     * @return mixed
     */
    public function getRoyalRequiredGiftWrapping()
    {
        return $this->getOrder()->getRoyalRequiredGiftWrapping();
    }

    /**
     * Retrieve Royal Required Shopping bag
     * 
     * @return mixed
     */
    public function getRoyalRequiredShoppingBag()
    {
        return $this->getOrder()->getRoyalRequiredShoppingBag();
    }

    /**
     * Retrieve Royal Type Gift Wrapping
     * 
     * @return mixed
     */
    public function getRoyalTypeGiftWrapping()
    {
        return $this->getOrder()->getRoyalTypeGiftWrapping();
    }

    /**
     * Retrieve Royal Noshi Code
     * 
     * @return mixed
     */
    public function getNoshiCode()
    {
        return $this->getOrder()->getNoshiCode();
    }

    /**
     * Retrieve Royal Receiver Name 1
     * 
     * @return mixed
     */
    public function getNoshiReceiverName1()
    {
        return $this->getOrder()->getRoyalNoshiReceiverName1();
    }

    /**
     * Retrieve Royal Receiver Name 2
     * 
     * @return mixed
     */
    public function getNoshiReceiverName2()
    {
        return $this->getOrder()->getRoyalNoshiReceiverName2();
    }


}