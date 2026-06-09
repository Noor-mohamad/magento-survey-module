<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ResponseDetail extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magic_survey_response_detail', 'detail_id');
    }
}
