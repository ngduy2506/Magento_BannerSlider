<?php

namespace Duy\BannerSlider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Image extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path
     */
    const URL_PATH_EDIT = 'duy_banner_slider/slider/edit';

    /**
     * @var \Duy\BannerSlider\Model\Slider
     */
    protected $slider;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Duy\BannerSlider\Model\Slider $slider
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Duy\BannerSlider\Model\Slider $slider,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->slider = $slider;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $slider = new \Magento\Framework\DataObject($item);
                $item[$fieldName . '_src'] = $this->slider->getImageUrl($slider['image']);
                $item[$fieldName . '_orig_src'] = $this->slider->getImageUrl($slider['image']);
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    self::URL_PATH_EDIT,
                    ['id' => $slider['id']]
                );
                $item[$fieldName . '_alt'] = $slider['name'];
            }
        }
        return $dataSource;
    }
}
