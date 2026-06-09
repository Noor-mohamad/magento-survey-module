<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class SurveyAction extends Column
{
    private UrlInterface $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $name = $this->getData('name');
            if (isset($item['survey_id'])) {
                $item[$name]['edit'] = [
                    'href'  => $this->urlBuilder->getUrl('magicsurvey/survey/edit', ['survey_id' => $item['survey_id']]),
                    'label' => __('Edit'),
                ];
                $item[$name]['delete'] = [
                    'href'    => $this->urlBuilder->getUrl('magicsurvey/survey/delete', ['survey_id' => $item['survey_id']]),
                    'label'   => __('Delete'),
                    'confirm' => [
                        'title'   => __('Delete Survey'),
                        'message' => __('Are you sure you want to delete this survey?'),
                    ],
                    'post'    => true,
                ];
            }
        }

        return $dataSource;
    }
}
