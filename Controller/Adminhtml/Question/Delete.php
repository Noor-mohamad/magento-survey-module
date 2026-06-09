<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Question;

use Magic\Survey\Model\QuestionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private QuestionFactory $questionFactory;

    public function __construct(Context $context, QuestionFactory $questionFactory)
    {
        parent::__construct($context);
        $this->questionFactory = $questionFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $questionId     = (int) $this->getRequest()->getParam('question_id');
        $surveyId       = (int) $this->getRequest()->getParam('survey_id');

        if (!$questionId) {
            $this->messageManager->addErrorMessage(__('We can\'t find a question to delete.'));
            return $resultRedirect->setPath('*/survey/edit', ['survey_id' => $surveyId]);
        }

        try {
            $question = $this->questionFactory->create()->load($questionId);
            $surveyId = $surveyId ?: (int) $question->getSurveyId();
            $question->delete();
            $this->messageManager->addSuccessMessage(__('The question has been deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/survey/edit', ['survey_id' => $surveyId]);
    }
}
