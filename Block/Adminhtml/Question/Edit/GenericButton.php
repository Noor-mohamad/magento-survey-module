<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Question\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    protected Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getQuestionId(): ?int
    {
        $id = $this->context->getRequest()->getParam('question_id');
        return $id ? (int) $id : null;
    }

    public function getSurveyId(): int
    {
        return (int) $this->context->getRequest()->getParam('survey_id');
    }

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
