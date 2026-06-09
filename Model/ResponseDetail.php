<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\ResponseDetailInterface;
use Magento\Framework\Model\AbstractModel;

class ResponseDetail extends AbstractModel implements ResponseDetailInterface
{
    public function _construct()
    {
        $this->_init(\Magic\Survey\Model\ResourceModel\ResponseDetail::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetailId(): ?int
    {
        return $this->getData(self::DETAIL_ID) === null ? null : (int) $this->getData(self::DETAIL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setDetailId(int $detailId): self
    {
        return $this->setData(self::DETAIL_ID, $detailId);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseId(): int
    {
        return (int) $this->getData(self::RESPONSE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseId(int $responseId): self
    {
        return $this->setData(self::RESPONSE_ID, $responseId);
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
