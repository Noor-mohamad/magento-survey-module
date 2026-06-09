<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Api;

interface SurveyManagementInterface
{
    /**
     * Get the currently active survey (first enabled survey within date range).
     *
     * @return \Magic\Survey\Api\Data\SurveyInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If no active survey exists.
     */
    public function getActiveSurvey(): \Magic\Survey\Api\Data\SurveyInterface;

    /**
     * Get all questions (with nested answer options) for a given survey.
     *
     * @param int $surveyId
     * @return \Magic\Survey\Api\Data\QuestionInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException If the survey does not exist.
     */
    public function getSurveyQuestions(int $surveyId): array;

    /**
     * Submit a customer's survey response.
     *
     * @param int    $surveyId      ID of the survey being answered.
     * @param int    $orderId       Order ID associated with this response.
     * @param string $customerEmail Email address of the respondent.
     * @param \Magic\Survey\Api\Data\AnswerDataInterface[] $answers Array of answers.
     * @return bool True on success.
     * @throws \Magento\Framework\Exception\LocalizedException On validation or save failure.
     */
    public function submitSurvey(
        int $surveyId,
        int $orderId,
        string $customerEmail,
        array $answers
    ): bool;
}
