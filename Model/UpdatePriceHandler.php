<?php


namespace TenxEngineer\ProductTierPriceCommitField\Model;


use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Locale\FormatInterface;
use Magento\Store\Model\StoreManagerInterface;

class UpdatePriceHandler extends \Magento\Catalog\Model\Product\Attribute\Backend\TierPrice\UpdateHandler
{
    private $tierPriceResource;

    public function __construct(
        StoreManagerInterface $storeManager,
        ProductAttributeRepositoryInterface $attributeRepository,
        GroupManagementInterface $groupManagement,
        MetadataPool $metadataPool,
        Tierprice $tierPriceResource,
        FormatInterface $localeFormat = null
    )
    {
        parent::__construct($storeManager, $attributeRepository, $groupManagement, $metadataPool, $tierPriceResource, $localeFormat);
        $this->tierPriceResource = $tierPriceResource;
    }

    /**
     * Update existing tier prices for processed product
     *
     * @param array $valuesToUpdate
     * @param array $oldValues
     * @return bool
     */
    public function updateValues(array $valuesToUpdate, array $oldValues): bool
    {

        $isChanged = false;
        foreach ($valuesToUpdate as $key => $value) {
            if ((!empty($value['value']) && (float)$oldValues[$key]['price'] !== (float)$value['value'])
                || $this->getPercentage($oldValues[$key]) !== $this->getPercentage($value)
                || $this->getSecondaryUnit($oldValues[$key]) !== $this->getSecondaryUnit($value)
            ) {
                $price = new \Magento\Framework\DataObject(
                    [
                        'value_id' => $oldValues[$key]['price_id'],
                        'value' => $value['value'],
                        'percentage_value' => $this->getPercentage($value),
                        'tier_price_custom_field' => $this->getSecondaryUnit($value),
                    ]
                );
                $this->tierPriceResource->savePriceData($price);
                $isChanged = true;
            }
        }

        return $isChanged;
    }

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
