<?php


namespace Duy\BannerSlider\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Duy\BannerSlider\Model\Slider\ImageUploader;

class Slider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_imageUploader;

    protected function _construct()
    {
        $this->_init('duy_bannerslider_slider', 'id');
    }


    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $name = $object->getName();
        $url = $object->getUrl();
        $image = $object->getImage();
        $groupId = $object->getGroupId();

        if (empty($name)) {
            throw new LocalizedException(__('The Slider name is required.'));
        }

        if (is_array($image)) {
            $object->setImage($image[0]['name']);
        }

        // if the URL not null then check the URL
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new LocalizedException(__('The URL Link is invalid.'));
        }


        return $this;
    }

    /**
     * Perform actions after object delete
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $imageName = $object->getImage();
        $this->_getImageUploader()->deleteImage($imageName);

        return $this;
    }

    /**
     * Get ImageUploader instance
     *
     * @return ImageUploader
     */
    private function _getImageUploader()
    {
        if ($this->_imageUploader === null) {
            $this->_imageUploader = ObjectManager::getInstance()->get(ImageUploader::class);
        }
        return $this->_imageUploader;
    }
}
