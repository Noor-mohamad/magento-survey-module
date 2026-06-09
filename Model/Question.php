<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\QuestionInterface;
use Magento\Framework\Model\AbstractModel;

class Question extends AbstractModel implements QuestionInterface
{
    public function _construct()
    {
        $this->_init(\Magic\Survey\Model\ResourceModel\Question::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionId(): ?int
    {
        return $this->getData(self::QUESTION_ID) === null ? null : (int) $this->getData(self::QUESTION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestionId(int $questionId): self
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSurveyId(): int
    {
        return (int) $this->getData(self::SURVEY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setSurveyId(int $surveyId): self
    {
        return $this->setData(self::SURVEY_ID, $surveyId);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title): self
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return (string) ($this->getData(self::TYPE) ?? self::TYPE_RADIO);
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): self
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsRequired(): int
    {
        return (int) $this->getData(self::IS_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsRequired(int $isRequired): self
    {
        return $this->setData(self::IS_REQUIRED, $isRequired);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder(): int
    {
        return (int) $this->getData(self::SORT_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder(int $sortOrder): self
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->getData('options') ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): self
    {
        return $this->setData('options', $options);
    }
}
