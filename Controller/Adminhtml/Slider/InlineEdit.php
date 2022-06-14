<?php

namespace Duy\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $sliderId) {
                    /** @var \Duy\BannerSlider\Model\Slider $model */
                    $model = $this->_objectManager->create('Duy\BannerSlider\Model\Slider');
                    $model->load($sliderId);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$sliderId]));
                        $model->save();
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithSliderId(
                            $model,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add slider name to error message
     *
     * @param \Duy\BannerSlider\Model\Slider $slider
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithSliderId(\Duy\BannerSlider\Model\Slider $slider, $errorText)
    {
        return '[Slider ID: ' . $slider->getId() . '] ' . $errorText;
    }

    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Duy_BannerSlider::slider_create') ||
        $this->_authorization->isAllowed('Duy_BannerSlider::slider_update');
    }
}
