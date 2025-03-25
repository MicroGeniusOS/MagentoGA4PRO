<?php
/**
 * GA4Pro Analytics Dashboard Controller
 */
namespace Magento\GA4Pro\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Check ACL permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_GA4Pro::dashboard');
    }

    /**
     * Dashboard index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_GA4Pro::dashboard');
        $resultPage->getConfig()->getTitle()->prepend(__('GA4 Analytics Dashboard'));
        $resultPage->addBreadcrumb(__('GA4 Analytics'), __('GA4 Analytics'));
        $resultPage->addBreadcrumb(__('Dashboard'), __('Dashboard'));
        
        return $resultPage;
    }
}