<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\AnswerDataInterface;
use Magic\Survey\Api\Data\QuestionInterface;
use Magic\Survey\Api\Data\SurveyInterface;
use Magic\Survey\Api\SurveyManagementInterface;
use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Survey\CollectionFactory as SurveyCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class SurveyManagement implements SurveyManagementInterface
{
    private SurveyCollectionFactory $surveyCollectionFactory;
    private SurveyFactory $surveyFactory;
    private QuestionCollectionFactory $questionCollectionFactory;
    private OptionCollectionFactory $optionCollectionFactory;
    private ResponseFactory $responseFactory;
    private ResponseDetailFactory $responseDetailFactory;

    public function __construct(
        SurveyCollectionFactory $surveyCollectionFactory,
        SurveyFactory $surveyFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        OptionCollectionFactory $optionCollectionFactory,
        ResponseFactory $responseFactory,
        ResponseDetailFactory $responseDetailFactory
    ) {
        $this->surveyCollectionFactory   = $surveyCollectionFactory;
        $this->surveyFactory             = $surveyFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->optionCollectionFactory   = $optionCollectionFactory;
        $this->responseFactory           = $responseFactory;
        $this->responseDetailFactory     = $responseDetailFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveSurvey(): SurveyInterface
    {
        $today = date('Y-m-d');

        /** @var \Magic\Survey\Model\Survey $survey */
        $survey = $this->surveyCollectionFactory->create()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', [['null' => true], ['lteq' => $today]])
            ->addFieldToFilter('end_date', [['null' => true], ['gteq' => $today]])
            ->setOrder('survey_id', 'ASC')
            ->setPageSize(1)
            ->getFirstItem();

        if (!$survey->getId()) {
            throw new NoSuchEntityException(__('No active survey found.'));
        }

        return $survey;
    }

    /**
     * {@inheritdoc}
     */
    public function getSurveyQuestions(int $surveyId): array
    {
        $survey = $this->surveyFactory->create()->load($surveyId);
        if (!$survey->getId()) {
            throw new NoSuchEntityException(__('Survey with ID "%1" does not exist.', $surveyId));
        }

        $questions = $this->questionCollectionFactory->create()
            ->addFieldToFilter('survey_id', $surveyId)
            ->setOrder('sort_order', 'ASC')
            ->getItems();

        foreach ($questions as $question) {
            /** @var \Magic\Survey\Model\Question $question */
            $options = [];
            if (in_array($question->getType(), [
                QuestionInterface::TYPE_RADIO,
                QuestionInterface::TYPE_CHECKBOX,
                QuestionInterface::TYPE_DROPDOWN,
            ])) {
                $options = $this->optionCollectionFactory->create()
                    ->addFieldToFilter('question_id', $question->getId())
                    ->setOrder('sort_order', 'ASC')
                    ->getItems();
            }
            $question->setOptions(array_values($options));
        }

        return array_values($questions);
    }

    /**
     * {@inheritdoc}
     */
    public function submitSurvey(
        int $surveyId,
        int $orderId,
        string $customerEmail,
        array $answers
    ): bool {
        if (!$surveyId) {
            throw new LocalizedException(__('Survey ID is required.'));
        }

        if (empty($answers)) {
            throw new LocalizedException(__('At least one answer is required.'));
        }

        $response = $this->responseFactory->create();
        $response->setSurveyId($surveyId);
        $response->setOrderId($orderId ?: null);
        $response->setCustomerEmail($customerEmail ?: null);
        $response->save();

        foreach ($answers as $answer) {
            /** @var AnswerDataInterface $answer */
            $detail = $this->responseDetailFactory->create();
            $detail->setResponseId((int) $response->getId());
            $detail->setQuestionId($answer->getQuestionId());
            $detail->setOptionId($answer->getOptionId());
            $detail->setAnswerText($answer->getAnswerText());
            $detail->save();
        }

        return true;
    }
}
