<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Survey\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getSurveyId()) {
            $data = [
                'label'      => __('Delete Survey'),
                'class'      => 'delete',
                'on_click'   => 'deleteConfirm(\'' . __(
                    'Are you sure you want to delete this survey?'
                ) . '\', \'' . $this->getUrl('*/*/delete', ['survey_id' => $this->getSurveyId()]) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }
}
