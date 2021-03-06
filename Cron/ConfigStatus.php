<?php
/**
 * ConfigStatus
 *
 * @copyright Copyright © 2022 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace Duy\BannerSlider\Cron;

use \Duy\BannerSlider\Model\SliderFactory;
use \Magento\Framework\Stdlib\DateTime\DateTime;

class ConfigSlideStatus
{
    protected $slideFactory;
    protected $date;

    public function __construct(
        SliderFactory $sliderFactory,
        DateTime     $date
    )
    {
        $this->sliderFactory = $sliderFactory;
        $this->date = $date;
    }

    public function disableSlider()
    {
        // get current date
        $currentDate = $this->date->gmtDate();
        // get all slides
        $slides = $this->slideFactory->create()->getCollection()->getData();
        foreach ($slides as $slide) {
            // compare and set disable if dis_from > current date or dis_to < current date
            if (strtotime($slide['display_from']) > strtotime($currentDate) || strtotime($slide['display_to']) < strtotime($currentDate)) {
                if ($slide['status'] == 1) {
                    $slide['status'] = 0;
                    $model = $this->sliderFactory->create();
                    $model->setData($slide);
                    $model->save();
                }
            }
        }
    }

    public function enableSlider()
    {
        // get current date
        $currentDate = $this->date->gmtDate();
        // get all slides if status = 0
        $slides = $this->sliderFactory->create()->getCollection()->addFieldToFilter('status', 0)->getData();
        foreach ($slides as $slide) {
            // compare and set enable if dis_from <= current date or dis_to >= current date
            if (strtotime($slide['display_from']) <= strtotime($currentDate) && strtotime($slide['display_to']) >= strtotime($currentDate)) {
                $slide['status'] = 1;
                $model = $this->sliderFactory->create();
                $model->setData($slide);
                $model->save();
            }
        }
    }
}


