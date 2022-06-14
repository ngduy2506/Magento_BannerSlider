<?php

namespace Duy\BannerSlider\Model\Config\Source;

use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Catalog\Model\Category as CatalogCategory;
use Magento\Framework\Data\OptionSourceInterface;

class PageType implements OptionSourceInterface
{

    const ALL_PAGE = 1;
    const HOME_PAGE = 2;
    const PRODUCT_PAGE = 3;
    const CATEGORY_PAGE = 4;
    const CUSTOM_WIDGET = 5;

    public function getOptionArray()
    {
        $optionArray = [];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => self::ALL_PAGE,  'label' => __('All Pages')],
            ['value' => self::HOME_PAGE,  'label' => __('Home Page')],
            ['value' => self::PRODUCT_PAGE,  'label' => __('Product Pages')],
            ['value' => self::CATEGORY_PAGE,  'label' => __('Catalog Pages')],
            ['value' => self::CUSTOM_WIDGET,  'label' => __('Custom Widget')],
        ];

        return $options;
    }


    public function getIdentityTagsMapByPageType()
    {
        return [
            self::HOME_PAGE => [],
            self::PRODUCT_PAGE => [CatalogProduct::CACHE_TAG],
            self::CATEGORY_PAGE => [CatalogCategory::CACHE_TAG],
            self::CUSTOM_WIDGET => [CatalogProduct::CACHE_TAG, CatalogCategory::CACHE_TAG],
        ];
    }
}
