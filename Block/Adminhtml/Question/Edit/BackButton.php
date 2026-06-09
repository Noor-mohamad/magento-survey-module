<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label'      => __('Back to Survey'),
            'on_click'   => sprintf(
                "location.href = '%s';",
                $this->getUrl('magicsurvey/survey/edit', ['survey_id' => $this->getSurveyId()])
            ),
            'class'      => 'back',
            'sort_order' => 10,
        ];
    }
}
