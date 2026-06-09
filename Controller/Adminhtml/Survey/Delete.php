<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Survey;

use Magic\Survey\Model\SurveyFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private SurveyFactory $surveyFactory;

    public function __construct(Context $context, SurveyFactory $surveyFactory)
    {
        parent::__construct($context);
        $this->surveyFactory = $surveyFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int) $this->getRequest()->getParam('survey_id');

        if (!$id) {
            $this->messageManager->addErrorMessage(__('We can\'t find a survey to delete.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $survey = $this->surveyFactory->create()->load($id);
            $survey->delete();
            $this->messageManager->addSuccessMessage(__('The survey has been deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
