<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Index;

use Magic\Survey\Model\ResponseDetailFactory;
use Magic\Survey\Model\ResponseFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Sales\Api\OrderRepositoryInterface;

class Submit extends Action
{
    private JsonFactory $jsonFactory;
    private Validator $formKeyValidator;
    private ResponseFactory $responseFactory;
    private ResponseDetailFactory $responseDetailFactory;
    private CustomerSession $customerSession;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Validator $formKeyValidator,
        ResponseFactory $responseFactory,
        ResponseDetailFactory $responseDetailFactory,
        CustomerSession $customerSession,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory           = $jsonFactory;
        $this->formKeyValidator      = $formKeyValidator;
        $this->responseFactory       = $responseFactory;
        $this->responseDetailFactory = $responseDetailFactory;
        $this->customerSession       = $customerSession;
        $this->orderRepository       = $orderRepository;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();

        if (!$this->getRequest()->isPost() || !$this->formKeyValidator->validate($this->getRequest())) {
            return $result->setData(['success' => false, 'message' => __('Invalid request.')]);
        }

        $surveyId = (int) $this->getRequest()->getParam('survey_id');
        $orderId  = (int) $this->getRequest()->getParam('order_id');
        $answers  = $this->getRequest()->getParam('answers', []);

        if (!$surveyId || empty($answers)) {
            return $result->setData(['success' => false, 'message' => __('Missing required data.')]);
        }

        try {
            // Prefer email from the order (works for both guests and logged-in customers)
            $customerEmail = null;
            $customerId    = null;
            if ($orderId) {
                try {
                    $order         = $this->orderRepository->get($orderId);
                    $customerEmail = $order->getCustomerEmail();
                    $customerId    = $order->getCustomerId() ?: null;
                } catch (\Exception $e) {
                    // Fall back to session if order load fails
                }
            }
            if (!$customerEmail && $this->customerSession->isLoggedIn()) {
                $customerEmail = $this->customerSession->getCustomer()->getEmail();
                $customerId    = $this->customerSession->getCustomerId();
            }

            $response = $this->responseFactory->create();
            $response->setData([
                'survey_id'      => $surveyId,
                'customer_id'    => $customerId,
                'customer_email' => $customerEmail,
                'order_id'       => $orderId ?: null,
            ]);
            $response->save();

            foreach ($answers as $questionId => $answer) {
                if (is_array($answer)) {
                    foreach ($answer as $optionId) {
                        $this->saveDetail($response->getId(), (int) $questionId, (int) $optionId, null);
                    }
                } else {
                    $optionId   = is_numeric($answer) ? (int) $answer : null;
                    $answerText = !is_numeric($answer) ? (string) $answer : null;
                    $this->saveDetail($response->getId(), (int) $questionId, $optionId, $answerText);
                }
            }

            return $result->setData(['success' => true, 'message' => __('Thank you for completing the survey!')]);
        } catch (\Exception $e) {
            return $result->setData(['success' => false, 'message' => __('An error occurred. Please try again.')]);
        }
    }

    private function saveDetail(int $responseId, int $questionId, ?int $optionId, ?string $answerText): void
    {
        $detail = $this->responseDetailFactory->create();
        $detail->setData([
            'response_id' => $responseId,
            'question_id' => $questionId,
            'option_id'   => $optionId,
            'answer_text' => $answerText,
        ]);
        $detail->save();
    }
}
