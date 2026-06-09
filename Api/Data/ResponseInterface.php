<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface ResponseInterface
{
    const RESPONSE_ID    = 'response_id';
    const SURVEY_ID      = 'survey_id';
    const CUSTOMER_ID    = 'customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const ORDER_ID       = 'order_id';
    const SUBMITTED_AT   = 'submitted_at';

    /**
     * Get response ID.
     *
     * @return int|null
     */
    public function getResponseId(): ?int;

    /**
     * Set response ID.
     *
     * @param int $responseId
     * @return $this
     */
    public function setResponseId(int $responseId): self;

    /**
     * Get the ID of the survey this response is for.
     *
     * @return int
     */
    public function getSurveyId(): int;

    /**
     * Set the survey this response is for.
     *
     * @param int $surveyId
     * @return $this
     */
    public function setSurveyId(int $surveyId): self;

    /**
     * Get the customer ID of the respondent (null for guests).
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set the customer ID of the respondent.
     *
     * @param int|null $customerId
     * @return $this
     */
    public function setCustomerId(?int $customerId): self;

    /**
     * Get the email address of the respondent.
     *
     * @return string|null
     */
    public function getCustomerEmail(): ?string;

    /**
     * Set the email address of the respondent.
     *
     * @param string|null $customerEmail
     * @return $this
     */
    public function setCustomerEmail(?string $customerEmail): self;

    /**
     * Get the order ID associated with this survey response.
     *
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * Set the order ID associated with this survey response.
     *
     * @param int|null $orderId
     * @return $this
     */
    public function setOrderId(?int $orderId): self;

    /**
     * Get the timestamp when this response was submitted.
     *
     * @return string|null
     */
    public function getSubmittedAt(): ?string;

    /**
     * Set the submission timestamp.
     *
     * @param string $submittedAt
     * @return $this
     */
    public function setSubmittedAt(string $submittedAt): self;
}
