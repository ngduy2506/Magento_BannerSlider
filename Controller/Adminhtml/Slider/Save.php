<?php


namespace Duy\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Duy\BannerSlider\Model\Slider\ImageUploader;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        DateTime $dateTime,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
        $this->dateTime = $dateTime;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if (empty($data['id'])) {
                $data['id'] = null;
            }
            if (empty($data['display_from'])) {
                $data['display_from'] = null;
            } else {
                $data['display_from'] = $this->convertDate($data['display_from']);
            }
            if (empty($data['display_to'])) {
                $data['display_to'] = null;
            } else {
                $data['display_to'] = $this->convertDate($data['display_to']);
            }
            $imageName = '';
            if (!empty($data['image'])) {
                $imageName = $data['image'][0]['name'];
            }


            $model = $this->_objectManager->create('Duy\BannerSlider\Model\Slider')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This slider no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            
            $data['group_id'] = implode(',', $data['group_id']);
            $data['store_ids'] = implode(',', $data['store_ids']);
            $data['customer_group_ids'] = implode(',', $data['customer_group_ids']);
            // dd($data);
            $model->setData($data);

            try {
                if(!$this->validate($data)) {
                    $this->messageManager->addError('Display To cannot be equal or earlier than Display From.');
                    return $resultRedirect->setPath($this->_redirect->getRefererUrl());
                }
                $model->save();
                if ($imageName) {
                    $this->imageUploader->moveFileFromTmp($imageName);
                }
                $this->messageManager->addSuccess(__('You saved the slider.'));
                $this->dataPersistor->clear('banner_slider');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the slider.'));
            }

            $this->dataPersistor->set('banner_slider', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Prepare data after save
     *
     * @param array $data
     * @return $this
     * @throws LocalizedException
     */
     private function validate(array $data)
    {
        if (null != $data['display_from'] && null != $data['display_to']
        && strtotime($data['display_from']) >= strtotime($data['display_to'])
    ) {
        return false;
    }
    return true;
    }

    /**
     * Convert date
     *
     * @param string $dateFromForm
     * @return string
     */
    private function convertDate($dateFromForm)
    {
        return $this->dateTime->gmtDate(
            StdlibDateTime::DATETIME_PHP_FORMAT,
            strtotime($dateFromForm)
        );
    }

    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Duy_BannerSlider::slider_update') || $this->_authorization->isAllowed('Duy_BannerSlider::slider_create');
    }
}
