<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Resolver;

use Magic\Survey\Model\ResponseDetailFactory;
use Magic\Survey\Model\ResponseFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class SubmitSurvey implements ResolverInterface
{
    private ResponseFactory $responseFactory;
    private ResponseDetailFactory $responseDetailFactory;

    public function __construct(
        ResponseFactory $responseFactory,
        ResponseDetailFactory $responseDetailFactory
    ) {
        $this->responseFactory       = $responseFactory;
        $this->responseDetailFactory = $responseDetailFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $input = $args['input'] ?? [];

        $surveyId      = (int)   ($input['survey_id']      ?? 0);
        $orderId       = (int)   ($input['order_id']       ?? 0);
        $customerEmail = (string)($input['customer_email'] ?? '');
        $answers       =          $input['answers']        ?? [];

        if (!$surveyId) {
            throw new GraphQlInputException(__('survey_id is required.'));
        }
        if (!$orderId) {
            throw new GraphQlInputException(__('order_id is required.'));
        }
        if (!$customerEmail) {
            throw new GraphQlInputException(__('customer_email is required.'));
        }
        if (empty($answers)) {
            throw new GraphQlInputException(__('At least one answer is required.'));
        }

        /** @var \Magic\Survey\Model\Response $response */
        $response = $this->responseFactory->create();
        $response->setData([
            'survey_id'      => $surveyId,
            'order_id'       => $orderId,
            'customer_email' => $customerEmail,
            'customer_id'    => $context->getUserId() ?: null,
        ]);
        $response->save();

        foreach ($answers as $answer) {
            /** @var \Magic\Survey\Model\ResponseDetail $detail */
            $detail = $this->responseDetailFactory->create();
            $detail->setData([
                'response_id' => $response->getId(),
                'question_id' => (int) ($answer['question_id'] ?? 0),
                'option_id'   => isset($answer['option_id'])   ? (int)    $answer['option_id']   : null,
                'answer_text' => isset($answer['answer_text']) ? (string) $answer['answer_text'] : null,
            ]);
            $detail->save();
        }

        return true;
    }
}
