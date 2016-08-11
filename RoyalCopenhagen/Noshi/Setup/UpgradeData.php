<?php

namespace RoyalCopenhagen\Noshi\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @param QuoteSetupFactory $quoteSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }
    /**
     * Upgrades DB for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);

        /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        $setup->startSetup();

        //Add attributes to quote 
        $entityAttributesCodes = [
            'royal_required_gift_wrapping' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_purpose' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Celebration or Memorial
            'royal_type_gift_wrapping' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Ribbon or Noshi
            'royal_ribbon' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Ribbon type
            'royal_noshi_code' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, // Noshi code
            'royal_noshi_name' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //// Noshi name
            'royal_noshi_receiver_name_1' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_noshi_receiver_name_2' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_required_shopping_bag' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        ];

        foreach ($entityAttributesCodes as $code => $type) {
            $quoteInstaller->addAttribute('quote', $code, ['type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true,]);
        }

        //Add attributes to order and invoice
        $entityAttributesCodes = [
            'royal_required_gift_wrapping' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_purpose' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Celebration or Memorial
            'royal_type_gift_wrapping' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Ribbon or Noshi
            'royal_ribbon' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Ribbon type
            'royal_noshi_code' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, //Noshi code
            'royal_noshi_name' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, // Noshi name
            'royal_noshi_receiver_name_1' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_noshi_receiver_name_2' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'royal_required_shopping_bag' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        ];

        foreach ($entityAttributesCodes as $code => $type) {
            $salesInstaller->addAttribute('order', $code, ['type' => $type, 'length'=> 255, 'visible' => false,'nullable' => true,]);
            $salesInstaller->addAttribute('invoice', $code, ['type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true,]);
        }

        $setup->endSetup();
    }
}