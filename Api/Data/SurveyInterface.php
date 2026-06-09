<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface SurveyInterface
{
    const SURVEY_ID   = 'survey_id';
    const TITLE       = 'title';
    const DESCRIPTION = 'description';
    const STATUS      = 'status';
    const START_DATE  = 'start_date';
    const END_DATE    = 'end_date';
    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';

    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;

    /**
     * Get survey ID.
     *
     * @return int|null
     */
    public function getSurveyId(): ?int;

    /**
     * Set survey ID.
     *
     * @param int $surveyId
     * @return $this
     */
    public function setSurveyId(int $surveyId): self;

    /**
     * Get survey title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set survey title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self;

    /**
     * Get survey description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set survey description.
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self;

    /**
     * Get survey status (1 = Enabled, 0 = Disabled).
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set survey status.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * Get survey start date (Y-m-d format, nullable).
     *
     * @return string|null
     */
    public function getStartDate(): ?string;

    /**
     * Set survey start date.
     *
     * @param string|null $startDate
     * @return $this
     */
    public function setStartDate(?string $startDate): self;

    /**
     * Get survey end date (Y-m-d format, nullable).
     *
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * Set survey end date.
     *
     * @param string|null $endDate
     * @return $this
     */
    public function setEndDate(?string $endDate): self;

    /**
     * Get record creation timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set record creation timestamp.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * Get record last-updated timestamp.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set record last-updated timestamp.
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self;
}
