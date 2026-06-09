<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AnswerOption extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magic_survey_answer_option', 'option_id');
    }
}
