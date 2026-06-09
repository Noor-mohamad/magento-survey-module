<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\ResourceModel\AnswerOption;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magic\Survey\Model\AnswerOption::class,
            \Magic\Survey\Model\ResourceModel\AnswerOption::class
        );
    }
}
