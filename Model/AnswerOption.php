<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\AnswerOptionInterface;
use Magento\Framework\Model\AbstractModel;

class AnswerOption extends AbstractModel implements AnswerOptionInterface
{
    public function _construct()
    {
        $this->_init(\Magic\Survey\Model\ResourceModel\AnswerOption::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionId(): ?int
    {
        return $this->getData(self::OPTION_ID) === null ? null : (int) $this->getData(self::OPTION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionId(int $optionId): self
    {
        return $this->setData(self::OPTION_ID, $optionId);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionId(): int
    {
        return (int) $this->getData(self::QUESTION_ID);
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
    public function getLabel(): ?string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel(string $label): self
    {
        return $this->setData(self::LABEL, $label);
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
}
