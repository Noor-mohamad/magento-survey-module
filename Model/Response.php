<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model;

use Magic\Survey\Api\Data\ResponseInterface;
use Magento\Framework\Model\AbstractModel;

class Response extends AbstractModel implements ResponseInterface
{
    public function _construct()
    {
        $this->_init(\Magic\Survey\Model\ResourceModel\Response::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseId(): ?int
    {
        return $this->getData(self::RESPONSE_ID) === null ? null : (int) $this->getData(self::RESPONSE_ID);
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
    public function getCustomerId(): ?int
    {
        $val = $this->getData(self::CUSTOMER_ID);
        return $val === null ? null : (int) $val;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId(?int $customerId): self
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerEmail(): ?string
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerEmail(?string $customerEmail): self
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId(): ?int
    {
        $val = $this->getData(self::ORDER_ID);
        return $val === null ? null : (int) $val;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId(?int $orderId): self
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubmittedAt(): ?string
    {
        return $this->getData(self::SUBMITTED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setSubmittedAt(string $submittedAt): self
    {
        return $this->setData(self::SUBMITTED_AT, $submittedAt);
    }
}
