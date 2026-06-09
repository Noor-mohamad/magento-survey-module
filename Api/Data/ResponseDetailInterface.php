<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface ResponseDetailInterface
{
    const DETAIL_ID   = 'detail_id';
    const RESPONSE_ID = 'response_id';
    const QUESTION_ID = 'question_id';
    const OPTION_ID   = 'option_id';
    const ANSWER_TEXT = 'answer_text';

    /**
     * Get response detail ID.
     *
     * @return int|null
     */
    public function getDetailId(): ?int;

    /**
     * Set response detail ID.
     *
     * @param int $detailId
     * @return $this
     */
    public function setDetailId(int $detailId): self;

    /**
     * Get the ID of the parent survey response.
     *
     * @return int
     */
    public function getResponseId(): int;

    /**
     * Set the parent survey response ID.
     *
     * @param int $responseId
     * @return $this
     */
    public function setResponseId(int $responseId): self;

    /**
     * Get the ID of the question being answered.
     *
     * @return int
     */
    public function getQuestionId(): int;

    /**
     * Set the ID of the question being answered.
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self;

    /**
     * Get the selected answer option ID (used for radio, checkbox, dropdown questions).
     *
     * @return int|null
     */
    public function getOptionId(): ?int;

    /**
     * Set the selected answer option ID.
     *
     * @param int|null $optionId
     * @return $this
     */
    public function setOptionId(?int $optionId): self;

    /**
     * Get the free-text answer (used for text questions).
     *
     * @return string|null
     */
    public function getAnswerText(): ?string;

    /**
     * Set the free-text answer.
     *
     * @param string|null $answerText
     * @return $this
     */
    public function setAnswerText(?string $answerText): self;
}
