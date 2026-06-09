<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface QuestionInterface
{
    const QUESTION_ID = 'question_id';
    const SURVEY_ID   = 'survey_id';
    const TITLE       = 'title';
    const TYPE        = 'type';
    const IS_REQUIRED = 'is_required';
    const SORT_ORDER  = 'sort_order';

    const TYPE_RADIO    = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DROPDOWN = 'dropdown';
    const TYPE_TEXT     = 'text';

    /**
     * Get question ID.
     *
     * @return int|null
     */
    public function getQuestionId(): ?int;

    /**
     * Set question ID.
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self;

    /**
     * Get the ID of the survey this question belongs to.
     *
     * @return int
     */
    public function getSurveyId(): int;

    /**
     * Set the survey this question belongs to.
     *
     * @param int $surveyId
     * @return $this
     */
    public function setSurveyId(int $surveyId): self;

    /**
     * Get question text/title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set question text/title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self;

    /**
     * Get question type (radio, checkbox, dropdown, text).
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set question type.
     *
     * @param string $type One of TYPE_RADIO, TYPE_CHECKBOX, TYPE_DROPDOWN, TYPE_TEXT
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Get whether this question is required (1 = yes, 0 = no).
     *
     * @return int
     */
    public function getIsRequired(): int;

    /**
     * Set whether this question is required.
     *
     * @param int $isRequired
     * @return $this
     */
    public function setIsRequired(int $isRequired): self;

    /**
     * Get display sort order of this question within the survey.
     *
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * Set display sort order.
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): self;

    /**
     * Get answer options for this question (populated for radio, checkbox, dropdown types).
     *
     * @return \Magic\Survey\Api\Data\AnswerOptionInterface[]
     */
    public function getOptions(): array;

    /**
     * Set answer options for this question.
     *
     * @param \Magic\Survey\Api\Data\AnswerOptionInterface[] $options
     * @return $this
     */
    public function setOptions(array $options): self;
}
