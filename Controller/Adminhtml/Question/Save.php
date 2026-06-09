<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Question;

use Magic\Survey\Model\AnswerOptionFactory;
use Magic\Survey\Model\QuestionFactory;
use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Save extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_manage';

    private QuestionFactory $questionFactory;
    private AnswerOptionFactory $answerOptionFactory;
    private OptionCollectionFactory $optionCollectionFactory;

    public function __construct(
        Context $context,
        QuestionFactory $questionFactory,
        AnswerOptionFactory $answerOptionFactory,
        OptionCollectionFactory $optionCollectionFactory
    ) {
        parent::__construct($context);
        $this->questionFactory         = $questionFactory;
        $this->answerOptionFactory     = $answerOptionFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->getRequest()->getPostValue();

        if (!$data) {
            return $resultRedirect->setPath('*/survey/index');
        }

        $surveyId   = (int) ($data['survey_id'] ?? 0);
        $questionId = !empty($data['question_id']) ? (int) $data['question_id'] : null;
        $question   = $this->questionFactory->create();

        if ($questionId) {
            $question->load($questionId);
            if (!$question->getId()) {
                $this->messageManager->addErrorMessage(__('This question no longer exists.'));
                return $resultRedirect->setPath('*/*/index', ['survey_id' => $surveyId]);
            }
        }

        try {
            $question->setSurveyId($surveyId);
            $question->setTitle($data['title'] ?? '');
            $question->setType($data['type'] ?? 'radio');
            $question->setIsRequired((int) ($data['is_required'] ?? 0));
            $question->setSortOrder((int) ($data['sort_order'] ?? 0));
            $question->save();

            // Save answer options (for radio/checkbox/dropdown)
            $this->saveOptions($question->getId(), $data['options'] ?? []);

            $this->messageManager->addSuccessMessage(__('The question has been saved.'));

            return $resultRedirect->setPath('*/survey/edit', ['survey_id' => $surveyId]);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the question.'));
            return $resultRedirect->setPath('*/*/edit', [
                'question_id' => $questionId,
                'survey_id'   => $surveyId,
            ]);
        }
    }

    private function saveOptions(int $questionId, array $options): void
    {
        // Remove existing options for this question
        $existing = $this->optionCollectionFactory->create()
            ->addFieldToFilter('question_id', $questionId);
        foreach ($existing as $option) {
            $option->delete();
        }

        foreach ($options as $sortOrder => $optionData) {
            if (empty($optionData['label'])) {
                continue;
            }
            $option = $this->answerOptionFactory->create();
            $option->setQuestionId($questionId);
            $option->setLabel($optionData['label']);
            $option->setSortOrder((int) $sortOrder);
            $option->save();
        }
    }
}
