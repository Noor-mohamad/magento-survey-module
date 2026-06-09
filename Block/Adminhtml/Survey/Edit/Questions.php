<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Survey\Edit;

use Magic\Survey\Model\ResourceModel\Question\CollectionFactory;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class Questions extends Template
{
    protected $_template = 'Magic_Survey::survey/questions.phtml';

    private CollectionFactory $questionCollectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $questionCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->questionCollectionFactory = $questionCollectionFactory;
    }

    public function getSurveyId(): int
    {
        return (int) $this->getRequest()->getParam('survey_id');
    }

    public function getQuestions(): array
    {
        if (!$this->getSurveyId()) {
            return [];
        }
        return $this->questionCollectionFactory->create()
            ->addFieldToFilter('survey_id', $this->getSurveyId())
            ->setOrder('sort_order', 'ASC')
            ->getItems();
    }

    public function getAddQuestionUrl(): string
    {
        return $this->getUrl('magicsurvey/question/edit', ['survey_id' => $this->getSurveyId()]);
    }

    public function getEditQuestionUrl(int $questionId): string
    {
        return $this->getUrl('magicsurvey/question/edit', [
            'question_id' => $questionId,
            'survey_id'   => $this->getSurveyId(),
        ]);
    }

    public function getDeleteQuestionUrl(int $questionId): string
    {
        return $this->getUrl('magicsurvey/question/delete', [
            'question_id' => $questionId,
            'survey_id'   => $this->getSurveyId(),
        ]);
    }
}
