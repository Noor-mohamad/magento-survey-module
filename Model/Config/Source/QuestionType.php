<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class QuestionType implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'radio',    'label' => __('Radio (Single Choice)')],
            ['value' => 'checkbox', 'label' => __('Checkbox (Multiple Choice)')],
            ['value' => 'dropdown', 'label' => __('Dropdown')],
            ['value' => 'text',     'label' => __('Text (Open Answer)')],
        ];
    }
}
