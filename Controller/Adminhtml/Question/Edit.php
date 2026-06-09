<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Question;

use Magic\Survey\Model\QuestionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private PageFactory $resultPageFactory;
    private QuestionFactory $questionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        QuestionFactory $questionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->questionFactory   = $questionFactory;
    }

    public function execute()
    {
        $id       = (int) $this->getRequest()->getParam('question_id');
        $question = $this->questionFactory->create();

        if ($id) {
            $question->load($id);
            if (!$question->getId()) {
                $this->messageManager->addErrorMessage(__('This question no longer exists.'));
                return $this->resultRedirectFactory->create()
                    ->setPath('*/*/index', ['survey_id' => $this->getRequest()->getParam('survey_id')]);
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $question->getId() ? __('Edit Question') : __('New Question')
        );
        return $resultPage;
    }
}
