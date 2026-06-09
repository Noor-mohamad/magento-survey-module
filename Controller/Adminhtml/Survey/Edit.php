<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Survey;

use Magic\Survey\Model\SurveyFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private PageFactory $resultPageFactory;
    private SurveyFactory $surveyFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        SurveyFactory $surveyFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->surveyFactory     = $surveyFactory;
    }

    public function execute()
    {
        $id     = (int) $this->getRequest()->getParam('survey_id');
        $survey = $this->surveyFactory->create();

        if ($id) {
            $survey->load($id);
            if (!$survey->getId()) {
                $this->messageManager->addErrorMessage(__('This survey no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $survey->getId() ? __('Edit Survey: %1', $survey->getTitle()) : __('New Survey')
        );
        return $resultPage;
    }
}
