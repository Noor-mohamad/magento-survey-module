<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface AnswerOptionInterface
{
    const OPTION_ID   = 'option_id';
    const QUESTION_ID = 'question_id';
    const LABEL       = 'label';
    const SORT_ORDER  = 'sort_order';

    /**
     * Get answer option ID.
     *
     * @return int|null
     */
    public function getOptionId(): ?int;

    /**
     * Set answer option ID.
     *
     * @param int $optionId
     * @return $this
     */
    public function setOptionId(int $optionId): self;

    /**
     * Get the ID of the question this option belongs to.
     *
     * @return int
     */
    public function getQuestionId(): int;

    /**
     * Set the question this option belongs to.
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self;

    /**
     * Get the display label for this answer option.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Set the display label for this answer option.
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self;

    /**
     * Get display sort order of this option within the question.
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
}
