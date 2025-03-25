<?php
/**
 * GA4Pro Analytics Test Connection Controller
 */
namespace Magento\GA4Pro\Controller\Adminhtml\Test;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\GA4Pro\Model\Config;
use Magento\Framework\HTTP\Client\Curl;

class Connection extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'Magento_GA4Pro::config';

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Config $config
     * @param Curl $curl
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Config $config,
        Curl $curl
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->config = $config;
        $this->curl = $curl;
        parent::__construct($context);
    }

    /**
     * Test GA4 connection
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        try {
            $measurementId = $this->getRequest()->getParam('measurement_id');
            $apiSecret = $this->getRequest()->getParam('api_secret');

            if (empty($measurementId)) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Measurement ID is required.')
                ]);
            }

            // Validate format
            if (!preg_match('/^G-[A-Z0-9]+$/', $measurementId)) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Invalid Measurement ID format. Should be in the format G-XXXXXXXX.')
                ]);
            }

            // Test connection to GA4
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
            
            // Build the GA4 MP endpoint URL with validation event
            $url = 'https://www.google-analytics.com/mp/collect';
            $queryParams = [
                'measurement_id' => $measurementId
            ];
            
            if (!empty($apiSecret)) {
                $queryParams['api_secret'] = $apiSecret;
            }
            
            $endpoint = $url . '?' . http_build_query($queryParams);
            
            // Build a test event
            $payload = json_encode([
                'client_id' => 'test-client-id',
                'events' => [
                    [
                        'name' => 'test_connection',
                        'params' => [
                            'source' => 'GA4Pro Magento Module'
                        ]
                    ]
                ]
            ]);
            
            $this->curl->post($endpoint, $payload);
            $response = $this->curl->getBody();
            $statusCode = $this->curl->getStatus();
            
            if ($statusCode == 204 || $statusCode == 200) {
                return $result->setData([
                    'success' => true,
                    'message' => __('Connection to Google Analytics 4 is successful.')
                ]);
            } else {
                return $result->setData([
                    'success' => false,
                    'message' => __('Failed to connect to Google Analytics 4. Status code: %1, Response: %2', $statusCode, $response)
                ]);
            }
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => __('An error occurred: %1', $e->getMessage())
            ]);
        }
    }
}
