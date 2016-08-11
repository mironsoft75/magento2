<?php

namespace RoyalCopenhagen\Noshi\Model\ResourceModel;

class Noshi extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('royal_noshi_item', 'entity_id');
    }

    /**
     * Update gift wrapping status
     *
     * @param int $status new status can be 1 or 0
     * @param array $noshiIds target wrapping IDs
     * @return void
     */
    public function updateStatus($status, array $noshiIds)
    {
        $this->getConnection()->update(
            $this->getMainTable(),
            ['visibility' => (int)(bool)$status],
            ['entity_id IN(?)' => $noshiIds]
        );
    }

    /**
     * @param $code
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _loadVisibleNoshiByCode($code)
    {
        $select = $this->getConnection()->select()->from(
            ['cp' => $this->getMainTable()]
        )->where(
            'cp.code = ?',
            $code
        )->where(
            'cp.visibility = ?',
            1
        );
        return $this->getConnection()->fetchOne($select);
    }

    /**
     * @param $code
     * @return \Magento\Framework\DB\Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _loadNoshiByCode($code)
    {
        $select = $this->getConnection()->select()->from(
            ['cp' => $this->getMainTable()]
        )->where(
            'cp.code = ?',
            $code
        );

        return $this->getConnection()->fetchOne($select);
    }
}