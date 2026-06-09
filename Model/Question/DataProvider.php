<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Question;

use Magic\Survey\Model\ResourceModel\AnswerOption\CollectionFactory as OptionCollectionFactory;
use Magic\Survey\Model\ResourceModel\Question\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $collection;

    private DataPersistorInterface $dataPersistor;
    private OptionCollectionFactory $optionCollectionFactory;
    private RequestInterface $request;
    private array $loadedData = [];

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        OptionCollectionFactory $optionCollectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection              = $collectionFactory->create();
        $this->dataPersistor           = $dataPersistor;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->request                 = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $questionId = (int) $this->request->getParam('question_id');
        $surveyId   = (int) $this->request->getParam('survey_id');

        if ($questionId) {
            $this->collection->addFieldToFilter('question_id', $questionId);
        }

        foreach ($this->collection->getItems() as $question) {
            $data = $question->getData();
            $data['options'] = $this->loadOptions((int) $question->getId());
            $this->loadedData[$question->getId()] = $data;
        }

        // New question — pre-populate survey_id from the URL so the hidden field has a value
        if (empty($this->loadedData) && $surveyId) {
            $this->loadedData[''] = ['survey_id' => $surveyId, 'options' => []];
        }

        $persistedData = $this->dataPersistor->get('magic_survey_question');
        if (!empty($persistedData)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($persistedData);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('magic_survey_question');
        }

        return $this->loadedData;
    }

    private function loadOptions(int $questionId): array
    {
        $options = $this->optionCollectionFactory->create()
            ->addFieldToFilter('question_id', $questionId)
            ->setOrder('sort_order', 'ASC')
            ->getItems();

        $result = [];
        foreach ($options as $option) {
            $result[] = $option->getData();
        }
        return $result;
    }
}
