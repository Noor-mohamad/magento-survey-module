<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Model\Survey;

use Magic\Survey\Model\ResourceModel\Survey\Collection;
use Magic\Survey\Model\ResourceModel\Survey\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $collection;

    private DataPersistorInterface $dataPersistor;
    private array $loadedData = [];

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }

        $persistedData = $this->dataPersistor->get('magic_survey');
        if (!empty($persistedData)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($persistedData);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('magic_survey');
        }

        return $this->loadedData;
    }
}
