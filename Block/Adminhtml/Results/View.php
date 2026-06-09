<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Block\Adminhtml\Results;

use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magic\Survey\Model\ResourceModel\ResponseDetail\CollectionFactory as DetailCollectionFactory;
use Magic\Survey\Model\ResponseFactory;
use Magic\Survey\Model\SurveyFactory;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;

class View extends Template
{
    protected $_template = 'Magic_Survey::results/view.phtml';

    private ResponseFactory $responseFactory;
    private SurveyFactory $surveyFactory;
    private QuestionCollectionFactory $questionCollectionFactory;
    private DetailCollectionFactory $detailCollectionFactory;
    private OptionCollectionFactory $optionCollectionFactory;
    private OrderRepositoryInterface $orderRepository;

    private ?object $cachedResponse = null;
    private ?object $cachedOrder    = null;
    private array $optionLabels     = [];

    public function __construct(
        Context $context,
        ResponseFactory $responseFactory,
        SurveyFactory $surveyFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        DetailCollectionFactory $detailCollectionFactory,
        OptionCollectionFactory $optionCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->responseFactory           = $responseFactory;
        $this->surveyFactory             = $surveyFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->detailCollectionFactory   = $detailCollectionFactory;
        $this->optionCollectionFactory   = $optionCollectionFactory;
        $this->orderRepository           = $orderRepository;
    }

    public function getResponse()
    {
        if ($this->cachedResponse === null) {
            $id = (int) $this->getRequest()->getParam('response_id');
            $this->cachedResponse = $this->responseFactory->create()->load($id);
        }
        return $this->cachedResponse;
    }

    public function getSurvey(int $surveyId)
    {
        return $this->surveyFactory->create()->load($surveyId);
    }

    public function getOrder()
    {
        if ($this->cachedOrder === null) {
            $orderId = (int) $this->getResponse()->getOrderId();
            if ($orderId) {
                try {
                    $this->cachedOrder = $this->orderRepository->get($orderId);
                } catch (\Exception $e) {
                    $this->cachedOrder = false;
                }
            } else {
                $this->cachedOrder = false;
            }
        }
        return $this->cachedOrder ?: null;
    }

    public function getCustomerEmail(): string
    {
        $response = $this->getResponse();
        if ($response->getCustomerEmail()) {
            return $response->getCustomerEmail();
        }
        $order = $this->getOrder();
        if ($order) {
            return (string) $order->getCustomerEmail();
        }
        return '—';
    }

    public function getOrderIncrementId(): string
    {
        $order = $this->getOrder();
        if ($order) {
            return '#' . $order->getIncrementId();
        }
        $orderId = (int) $this->getResponse()->getOrderId();
        return $orderId ? '#' . $orderId : '—';
    }

    public function getQuestions(int $surveyId): array
    {
        return $this->questionCollectionFactory->create()
            ->addFieldToFilter('survey_id', $surveyId)
            ->setOrder('sort_order', 'ASC')
            ->getItems();
    }

    public function getAnswers(int $responseId): array
    {
        $details = $this->detailCollectionFactory->create()
            ->addFieldToFilter('response_id', $responseId)
            ->getItems();

        $answers = [];
        foreach ($details as $detail) {
            $answers[$detail->getQuestionId()][] = $detail;
        }
        return $answers;
    }

    public function getOptionLabel(int $optionId): string
    {
        if (empty($this->optionLabels)) {
            $options = $this->optionCollectionFactory->create()->getItems();
            foreach ($options as $opt) {
                $this->optionLabels[(int) $opt->getId()] = $opt->getLabel();
            }
        }
        return $this->optionLabels[$optionId] ?? "Option #{$optionId}";
    }

    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/');
    }
}
