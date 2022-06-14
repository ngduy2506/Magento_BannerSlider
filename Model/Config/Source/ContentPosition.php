<?php

namespace Duy\BannerSlider\Model\Config\Source;

/**
 * Class ContentPosition
 * @package Aheadworks\Rbslider\Model\Source
 */
class ContentPosition implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Position values
     */
    const CONTENT_TOP = 1;
    const CONTENT_BOTTOM = 2;
    const CONTENT_LEFT = 3;
    const CONTENT_RIGHT = 4;
    const CONTENT_CENTER = 5;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CONTENT_TOP,  'label' => __('Content top')],
            ['value' => self::CONTENT_BOTTOM,  'label' => __('Content bottom')],
            ['value' => self::CONTENT_LEFT,  'label' => __('Content left')],
            ['value' => self::CONTENT_RIGHT,  'label' => __('Content right')],
            ['value' => self::CONTENT_CENTER,  'label' => __('Content center')],
        ];
    }
}
