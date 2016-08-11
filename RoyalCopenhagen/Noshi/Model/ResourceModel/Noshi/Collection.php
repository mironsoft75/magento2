<?php

namespace RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            'RoyalCopenhagen\Noshi\Model\Noshi',
            'RoyalCopenhagen\Noshi\Model\ResourceModel\Noshi'
        );
    }
}