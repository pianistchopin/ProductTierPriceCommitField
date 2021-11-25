<?php


namespace TenxEngineer\ProductTierPriceCommitField\Model\Product\Attribute\Backend\TierPrice;


use Magento\Catalog\Model\Product\Attribute\Backend\TierPrice\SaveHandler;

class SavePriceHandler extends SaveHandler
{
    /**
     * Get additional tier price fields.
     *
     * @param array $objectArray
     * @return array
     */
    public function getAdditionalFields(array $objectArray): array
    {
        $percentageValue = $this->getPercentage($objectArray);

        return [
            'value' => $percentageValue ? null : $objectArray['price'],
            'percentage_value' => $percentageValue ?: null,
            'tier_price_custom_field' => $this->getSecondaryUnit($objectArray),
        ];
    }

    /**
     * @param array $priceRow
     * @return mixed|null
     */
    public function getSecondaryUnit(array  $priceRow)
    {
        return isset($priceRow['tier_price_custom_field']) && !empty($priceRow['tier_price_custom_field'])
            ? $priceRow['tier_price_custom_field']
            : null;
    }
}
