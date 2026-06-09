<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Data;

use Magic\Survey\Api\Data\AnswerDataInterface;
use Magento\Framework\DataObject;

class AnswerData extends DataObject implements AnswerDataInterface
{
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
    public function getOptionId(): ?int
    {
        $val = $this->getData(self::OPTION_ID);
        return $val === null ? null : (int) $val;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionId(?int $optionId): self
    {
        return $this->setData(self::OPTION_ID, $optionId);
    }

    /**
     * {@inheritdoc}
     */
    public function getAnswerText(): ?string
    {
        return $this->getData(self::ANSWER_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setAnswerText(?string $answerText): self
    {
        return $this->setData(self::ANSWER_TEXT, $answerText);
    }
}
