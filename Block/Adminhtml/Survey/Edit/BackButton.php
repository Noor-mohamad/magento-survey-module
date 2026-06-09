<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Survey\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label'    => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('magicsurvey/survey/')),
            'class'    => 'back',
            'sort_order' => 10,
        ];
    }
}
