<?php
/**
 * Copyright © Magic, Inc. All rights reserved.
 */

namespace Magic\Survey\Controller\Adminhtml\Results;

use Magic\Survey\Model\ResponseFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{
    const ADMIN_RESOURCE = 'Magic_Survey::survey_results';

    private PageFactory $resultPageFactory;
    private ResponseFactory $responseFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ResponseFactory $responseFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->responseFactory   = $responseFactory;
    }

    public function execute()
    {
        $id       = (int) $this->getRequest()->getParam('response_id');
        $response = $this->responseFactory->create()->load($id);

        if (!$response->getId()) {
            $this->messageManager->addErrorMessage(__('This response no longer exists.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Survey Response #%1', $id));
        return $resultPage;
    }
}
