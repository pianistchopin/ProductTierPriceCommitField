<?php


namespace TenxEngineer\ProductTierPriceCommitField\Model\ResourceModel\Product\Attribute\Backend;


class DataColumnUpdate extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
{
    /**
     * @param array $columns
     * @return array
     */
    protected function _loadPriceDataColumns($columns)
    {
        $columns = parent::_loadPriceDataColumns($columns);
        $columns['tier_price_custom_field'] = 'tier_price_custom_field';
        return $columns;
    }
}
