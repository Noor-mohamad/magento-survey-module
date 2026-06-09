<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\ResourceModel\ResponseDetail;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magic\Survey\Model\ResponseDetail::class,
            \Magic\Survey\Model\ResourceModel\ResponseDetail::class
        );
    }
}
