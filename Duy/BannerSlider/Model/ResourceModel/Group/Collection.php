<?php


namespace Duy\BannerSlider\Model\ResourceModel\Group;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Duy\BannerSlider\Model\Group', 'Duy\BannerSlider\Model\ResourceModel\Group');
    }
}
