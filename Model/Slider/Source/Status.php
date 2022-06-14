<?php

namespace Duy\BannerSlider\Model\Slider\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Duy\BannerSlider\Model\Slider
     */
    protected $slider;

    /**
     * Constructor
     *
     * @param \Duy\BannerSlider\Model\Slider $slider
     */
    public function __construct(\Duy\BannerSlider\Model\Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->slider->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
