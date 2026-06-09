<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Resolver;

use Magic\Survey\Api\Data\QuestionInterface;
use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magic\Survey\Model\SurveyFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class SurveyQuestions implements ResolverInterface
{
    private SurveyFactory $surveyFactory;
    private QuestionCollectionFactory $questionCollectionFactory;
    private OptionCollectionFactory $optionCollectionFactory;

    public function __construct(
        SurveyFactory $surveyFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        OptionCollectionFactory $optionCollectionFactory
    ) {
        $this->surveyFactory             = $surveyFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->optionCollectionFactory   = $optionCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $surveyId = (int) ($args['survey_id'] ?? 0);

        if (!$surveyId) {
            throw new GraphQlInputException(__('survey_id is required.'));
        }

        $survey = $this->surveyFactory->create()->load($surveyId);
        if (!$survey->getId()) {
            throw new GraphQlNoSuchEntityException(__('Survey with ID "%1" does not exist.', $surveyId));
        }

        $questions = $this->questionCollectionFactory->create()
            ->addFieldToFilter('survey_id', $surveyId)
            ->setOrder('sort_order', 'ASC')
            ->getItems();

        $result = [];
        foreach ($questions as $question) {
            $data    = $question->getData();
            $options = [];

            if (in_array($question->getType(), [
                QuestionInterface::TYPE_RADIO,
                QuestionInterface::TYPE_CHECKBOX,
                QuestionInterface::TYPE_DROPDOWN,
            ])) {
                $optionItems = $this->optionCollectionFactory->create()
                    ->addFieldToFilter('question_id', $question->getId())
                    ->setOrder('sort_order', 'ASC')
                    ->getItems();

                foreach ($optionItems as $option) {
                    $options[] = $option->getData();
                }
            }

            $data['options'] = $options;
            $result[]        = $data;
        }

        return $result;
    }
}
