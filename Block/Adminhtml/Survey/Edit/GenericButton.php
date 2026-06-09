<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Survey\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    protected Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getSurveyId(): ?int
    {
        $id = $this->context->getRequest()->getParam('survey_id');
        return $id ? (int) $id : null;
    }

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
