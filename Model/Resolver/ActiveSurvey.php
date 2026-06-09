<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Resolver;

use Magic\Survey\Model\ResourceModel\Survey\CollectionFactory as SurveyCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ActiveSurvey implements ResolverInterface
{
    private SurveyCollectionFactory $surveyCollectionFactory;

    public function __construct(SurveyCollectionFactory $surveyCollectionFactory)
    {
        $this->surveyCollectionFactory = $surveyCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $today = date('Y-m-d');

        /** @var \Magic\Survey\Model\Survey $survey */
        $survey = $this->surveyCollectionFactory->create()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', [['null' => true], ['lteq' => $today]])
            ->addFieldToFilter('end_date',   [['null' => true], ['gteq' => $today]])
            ->setOrder('survey_id', 'ASC')
            ->setPageSize(1)
            ->getFirstItem();

        if (!$survey->getId()) {
            throw new GraphQlNoSuchEntityException(__('No active survey found.'));
        }

        return $survey->getData();
    }
}
