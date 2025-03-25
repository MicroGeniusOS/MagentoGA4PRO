<?php
/**
 * GA4Pro Analytics Config Initialization Controller
 */
namespace Magento\GA4Pro\Controller\Adminhtml\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\GA4Pro\Helper\SystemConfigWorkaround;
use Magento\GA4Pro\Model\GA4;

class Initialize extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var SystemConfigWorkaround
     */
    protected $systemConfigWorkaround;

    /**
     * @var GA4
     */
    protected $ga4;

    /**
     * Constructor
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param SystemConfigWorkaround $systemConfigWorkaround
     * @param GA4 $ga4
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        SystemConfigWorkaround $systemConfigWorkaround,
        GA4 $ga4
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->systemConfigWorkaround = $systemConfigWorkaround;
        $this->ga4 = $ga4;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        
        try {
            // Initialize default configuration
            $this->systemConfigWorkaround->initializeDefaultConfiguration();
            
            // Check if we have parameters to set
            $measurementId = $this->getRequest()->getParam('measurement_id');
            $apiSecret = $this->getRequest()->getParam('api_secret');
            $debugMode = (bool)$this->getRequest()->getParam('debug_mode', false);
            
            if ($measurementId) {
                $this->systemConfigWorkaround->setMeasurementId($measurementId);
            }
            
            if ($apiSecret) {
                $this->systemConfigWorkaround->setApiSecret($apiSecret);
            }
            
            $this->systemConfigWorkaround->setDebugMode($debugMode);
            
            // Validate the connection if we have the measurement ID
            $validationResult = '';
            if ($measurementId) {
                $validationResult = $this->ga4->validateConnection($measurementId, $apiSecret);
            }
            
            return $result->setData([
                'success' => true,
                'message' => __('Configuration initialized successfully'),
                'validation' => $validationResult
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if user has enough permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_GA4Pro::config');
    }
}