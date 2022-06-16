<?php

namespace Duy\BannerSlider\Ui\Component\Listing\Column;

use Duy\BannerSlider\Model\Config\Source\PageType;

class Position extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $banner) {
                if ($banner['page_type'] == PageType::CUSTOM_WIDGET) {
                    $banner['position'] = '';
                }
            }
        }
        return $dataSource;
    }
}
