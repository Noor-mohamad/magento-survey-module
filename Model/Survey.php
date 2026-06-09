<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\SurveyInterface;
use Magento\Framework\Model\AbstractModel;

class Survey extends AbstractModel implements SurveyInterface
{
    public function _construct()
    {
        $this->_init(\Magic\Survey\Model\ResourceModel\Survey::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getSurveyId(): ?int
    {
        return $this->getData(self::SURVEY_ID) === null ? null : (int) $this->getData(self::SURVEY_ID);
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
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(?string $description): self
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(int $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getStartDate(): ?string
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate(?string $startDate): self
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getEndDate(): ?string
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setEndDate(?string $endDate): self
    {
        return $this->setData(self::END_DATE, $endDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
