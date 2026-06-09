<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Observer;

use Magic\Survey\Model\ResourceModel\Survey\CollectionFactory as SurveyCollectionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;

class OrderSuccess implements ObserverInterface
{
    private SurveyCollectionFactory $surveyCollectionFactory;
    private Registry $registry;

    public function __construct(
        SurveyCollectionFactory $surveyCollectionFactory,
        Registry $registry
    ) {
        $this->surveyCollectionFactory = $surveyCollectionFactory;
        $this->registry                = $registry;
    }

    public function execute(Observer $observer)
    {
        $today = date('Y-m-d');

        $survey = $this->surveyCollectionFactory->create()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', [['null' => true], ['lteq' => $today]])
            ->addFieldToFilter('end_date', [['null' => true], ['gteq' => $today]])
            ->setOrder('survey_id', 'ASC')
            ->setPageSize(1)
            ->getFirstItem();

        if ($survey->getId()) {
            $this->registry->register('current_survey', $survey);

            $orderId = $observer->getEvent()->getOrderIds();
            if (!empty($orderId)) {
                $this->registry->register('current_survey_order_id', reset($orderId));
            }
        }
    }
}
