<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block;

use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Survey extends Template
{
    private Registry $registry;
    private QuestionCollectionFactory $questionCollectionFactory;
    private OptionCollectionFactory $optionCollectionFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        QuestionCollectionFactory $questionCollectionFactory,
        OptionCollectionFactory $optionCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry                  = $registry;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->optionCollectionFactory   = $optionCollectionFactory;
    }

    public function getSurvey(): ?\Magic\Survey\Model\Survey
    {
        return $this->registry->registry('current_survey');
    }

    public function getOrderId(): ?int
    {
        return $this->registry->registry('current_survey_order_id');
    }

    public function getQuestions(): array
    {
        $survey = $this->getSurvey();
        if (!$survey) {
            return [];
        }

        $questions = $this->questionCollectionFactory->create()
            ->addFieldToFilter('survey_id', $survey->getId())
            ->setOrder('sort_order', 'ASC')
            ->getItems();

        $result = [];
        foreach ($questions as $question) {
            $options = [];
            if (in_array($question->getType(), ['radio', 'checkbox', 'dropdown'])) {
                $options = $this->optionCollectionFactory->create()
                    ->addFieldToFilter('question_id', $question->getId())
                    ->setOrder('sort_order', 'ASC')
                    ->getItems();
            }
            $result[] = ['question' => $question, 'options' => $options];
        }

        return $result;
    }

    public function getSubmitUrl(): string
    {
        return $this->getUrl('survey/index/submit');
    }
}
