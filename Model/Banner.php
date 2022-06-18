<?php


namespace Duy\BannerSlider\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;

class Banner extends \Magento\Framework\Model\AbstractModel
{

    const CACHE_TAG = 'duy_bannerslider_banner';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected $_storeManager;

    protected function _construct()
    {
        $this->_init('Duy\BannerSlider\Model\ResourceModel\Banner');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    public function beforeSave()
    {
        $groupName = $this->getData('name');
        $groupId = (int)$this->getData('id');
        $collection = $this->getCollection()->addFieldToFilter('name', $groupName);
        if ($groupId) {
            $collection = $collection->addFieldToFilter('id', ['neq' => $groupId]);
        }
        $group = $collection->getFirstItem();
        if ($group->getId()) {
            throw new LocalizedException(__('The Banner Name has already existed.'));
        }
        parent::beforeSave();
        return $this;
    }
     /**
     * Get StoreManagerInterface instance
     *
     * @return StoreManagerInterface
     */
    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}
