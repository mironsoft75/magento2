<?php

namespace RoyalCopenhagen\Noshi\Model\Source;

class Visibility implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $availableOptions = \RoyalCopenhagen\Noshi\Model\Noshi::getVisibilities();
        
        $options = [];
        foreach($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}