<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Survey;

use Magic\Survey\Model\SurveyFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private SurveyFactory $surveyFactory;
    private DataPersistorInterface $dataPersistor;

    public function __construct(
        Context $context,
        SurveyFactory $surveyFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->surveyFactory  = $surveyFactory;
        $this->dataPersistor  = $dataPersistor;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        // MySQL DATE columns reject empty strings — must be null
        foreach (['start_date', 'end_date'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        $id     = !empty($data['survey_id']) ? (int) $data['survey_id'] : null;
        $survey = $this->surveyFactory->create();

        if ($id) {
            $survey->load($id);
            if (!$survey->getId()) {
                $this->messageManager->addErrorMessage(__('This survey no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        try {
            $survey->setTitle($data['title'] ?? '');
            $survey->setDescription($data['description'] ?? null);
            $survey->setStatus((int) ($data['status'] ?? 1));
            $survey->setStartDate($data['start_date']);
            $survey->setEndDate($data['end_date']);

            $survey->save();
            $this->messageManager->addSuccessMessage(__('The survey has been saved.'));
            $this->dataPersistor->clear('magic_survey');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['survey_id' => $survey->getId()]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the survey.'));
            $this->dataPersistor->set('magic_survey', $data);
            return $resultRedirect->setPath('*/*/edit', ['survey_id' => $id]);
        }
    }
}
