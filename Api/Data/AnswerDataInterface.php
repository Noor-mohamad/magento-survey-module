<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api\Data;

interface AnswerDataInterface
{
    const QUESTION_ID  = 'question_id';
    const OPTION_ID    = 'option_id';
    const ANSWER_TEXT  = 'answer_text';

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
     * Get the selected option ID (for radio, checkbox, dropdown questions).
     *
     * @return int|null
     */
    public function getOptionId(): ?int;

    /**
     * Set the selected option ID.
     *
     * @param int|null $optionId
     * @return $this
     */
    public function setOptionId(?int $optionId): self;

    /**
     * Get the free-text answer (for text questions).
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
