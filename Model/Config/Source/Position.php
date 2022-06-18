<?php

namespace Duy\BannerSlider\Model\Config\Source;


class Position implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Position values
     */
    const MENU_TOP = 1;
    const CONTENT_TOP = 2;
    const PAGE_BOTTOM = 3;
    const MENU_BOTTOM = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::MENU_TOP,  'label' => __('Menu Top')],
            ['value' => self::CONTENT_TOP,  'label' => __('Content top')],
            ['value' => self::PAGE_BOTTOM,  'label' => __('Page Bottom')],
            ['value' => self::MENU_BOTTOM,  'label' => __('Menu Bottom')]
        ];
    }
}
