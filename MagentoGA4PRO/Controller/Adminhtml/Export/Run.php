<?php
/**
 * GA4Pro Analytics Export Controller
 */
namespace Magento\GA4Pro\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\GA4Pro\Helper\SystemConfigWorkaround;

class Run extends Action
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;
    
    /**
     * @var SystemConfigWorkaround
     */
    protected $configHelper;
    
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    
    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param SystemConfigWorkaround $configHelper
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        SystemConfigWorkaround $configHelper,
        DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->configHelper = $configHelper;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }
    
    /**
     * Check ACL permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_GA4Pro::export');
    }
    
    /**
     * Export GA4 data to file
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $format = $this->getRequest()->getParam('format', 'json');
            $dateRange = $this->getRequest()->getParam('date_range', '30days');
            
            // Handle custom date range
            if ($dateRange === 'custom') {
                $dateFrom = $this->getRequest()->getParam('date_from');
                $dateTo = $this->getRequest()->getParam('date_to');
                
                if (empty($dateFrom) || empty($dateTo)) {
                    throw new LocalizedException(__('Please provide both From and To dates for a custom date range.'));
                }
                
                // Process the date range and set it in the helper
                // This will be implemented in the exportData method of the helper
            }
            
            // Call the helper to export the data
            $exportedFilePath = $this->configHelper->exportData($format);
            
            // Get file content
            $content = file_get_contents($exportedFilePath);
            
            // Set appropriate content type
            $contentType = $this->getContentType($format);
            
            // Generate filename
            $fileName = 'ga4pro_export_' . date('Ymd') . '.' . $format;
            
            // Return file download response
            return $this->fileFactory->create(
                $fileName,
                [
                    'type' => 'string',
                    'value' => $content,
                    'rm' => true // Delete file after download
                ],
                DirectoryList::VAR_DIR,
                $contentType
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('An error occurred during the GA4 data export. Please check the logs for details.')
            );
        }
        
        // Redirect back to dashboard on error
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('ga4pro/dashboard/index');
        return $resultRedirect;
    }
    
    /**
     * Get appropriate content type for the export format
     *
     * @param string $format
     * @return string
     */
    private function getContentType($format)
    {
        switch ($format) {
            case 'json':
                return 'application/json';
            case 'csv':
                return 'text/csv';
            case 'xml':
                return 'application/xml';
            default:
                return 'application/octet-stream';
        }
    }
}