<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        if (!$this->getQuestionId()) {
            return [];
        }
        return [
            'label'      => __('Delete Question'),
            'class'      => 'delete',
            'on_click'   => 'deleteConfirm(\'' . __(
                'Are you sure you want to delete this question?'
            ) . '\', \'' . $this->getUrl('magicsurvey/question/delete', [
                'question_id' => $this->getQuestionId(),
                'survey_id'   => $this->getSurveyId(),
            ]) . '\')',
            'sort_order' => 20,
        ];
    }
}
