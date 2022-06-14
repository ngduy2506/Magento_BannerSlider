<?php

namespace Duy\BannerSlider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Duy\BannerSlider\Model\Slider\FileInfo;

class BannerSlider extends Template implements BlockInterface
{
    /**
     * @var \Duy\BannerSlider\Model\SliderFactory
     */
    protected $sliderFactory;
    protected $bannerFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Duy\BannerSlider\Model\SliderFactory $sliderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Duy\BannerSlider\Model\SliderFactory $sliderFactory,
        \Duy\BannerSlider\Model\BannerFactory $bannerFactory,
        array $data = []
    ) {
        $this->sliderFactory = $sliderFactory;
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve the banner slider
     *
     * @param \Duy\BannerSlider\Model\Slider[]
     */
    public function getSliders()
    {
        $result = [];
        $banner = $this->getBanner();
        if ($banner) {
            $banner_id = $banner['id'];
            $slides = $this->sliderFactory->create()->getCollection()->addFieldToFilter(
                'group_id', ['like' => "%" . $banner_id . "%"]
            );
            foreach ($slides->getData() as $slide) {
                if (in_array($banner_id, explode(',', $slide['group_id']))) {
                    $url = $this->getImageUrl($slide['image']);
                    $slide['image'] = $url;
                    array_push($result, $slide);
                };
            }
        }
        return $result;
    }
    public function getBanner()
    {
    
        $currentPage = $this->getRequest()->getFullActionName();
        if ($currentPage == "cms_index_index") {
            $collection = $this->bannerFactory->create()->getCollection()->addFieldToFilter(
                'page_type', [1, 2]
            )->setOrder('priority', 'ASC')->getFirstItem()->getData();
        }
        if ($currentPage == "catalog_product_view") {
            $collection = $this->bannerFactory->create()->getCollection()->addFieldToFilter(
                'page_type', [1, 3]
            )->setOrder('priority', 'ASC')->getFirstItem()->getData();
        }
        if ($currentPage == "catalog_category_view") {
            $collection = $this->bannerFactory->create()->getCollection()->addFieldToFilter(
                'page_type', [1, 4]
            )->setOrder('priority', 'ASC')->getFirstItem()->getData();
        }
        return $collection;
    }
    public function getImageUrl($imageName)
    {
        $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . FileInfo::ENTITY_MEDIA_PATH . '/' . $imageName;
        return $url;
    }

    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }

}
