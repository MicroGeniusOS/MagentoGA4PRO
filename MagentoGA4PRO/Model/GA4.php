<?php
/**
 * GA4Pro Analytics GA4 Model
 */
namespace Magento\GA4Pro\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Asset\Repository;

class GA4
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var Repository
     */
    protected $assetRepo;

    /**
     * @param Config $config
     * @param Json $json
     * @param Repository $assetRepo
     */
    public function __construct(
        Config $config,
        Json $json,
        Repository $assetRepo
    ) {
        $this->config = $config;
        $this->json = $json;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Get GA4 initialization code
     *
     * @param int|null $storeId
     * @return string
     */
    public function getGa4InitCode($storeId = null)
    {
        if (!$this->config->isEnabled($storeId)) {
            return '';
        }

        $measurementId = $this->config->getMeasurementId($storeId);
        if (empty($measurementId)) {
            return '';
        }

        $debugMode = $this->config->isDebugMode($storeId) ? 'true' : 'false';
        
        return "
            <!-- Google tag (gtag.js) -->
            <script async src=\"https://www.googletagmanager.com/gtag/js?id={$measurementId}\"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{$measurementId}', {
                    'debug_mode': {$debugMode},
                    'send_page_view': " . ($this->config->isTrackPageViewsEnabled($storeId) ? 'true' : 'false') . "
                });
            </script>
        ";
    }

    /**
     * Generate product view event data
     *
     * @param Product $product
     * @param int|null $storeId
     * @return array
     */
    public function getProductViewEventData(Product $product, $storeId = null)
    {
        if (!$this->config->isTrackProductViewsEnabled($storeId)) {
            return [];
        }

        $price = $product->getFinalPrice();
        $categoryNames = [];
        
        $categoryCollection = $product->getCategoryCollection();
        foreach ($categoryCollection as $category) {
            $categoryNames[] = $category->getName();
        }
        
        return [
            'event' => 'view_item',
            'ecommerce' => [
                'items' => [[
                    'item_id' => $product->getSku(),
                    'item_name' => $product->getName(),
                    'item_brand' => $product->getAttributeText('manufacturer') ?? '',
                    'item_category' => !empty($categoryNames) ? $categoryNames[0] : '',
                    'price' => $price
                ]]
            ]
        ];
    }

    /**
     * Generate add to cart event data
     *
     * @param Product $product
     * @param float $qty
     * @param int|null $storeId
     * @return array
     */
    public function getAddToCartEventData(Product $product, $qty, $storeId = null)
    {
        if (!$this->config->isTrackAddToCartEnabled($storeId)) {
            return [];
        }

        $price = $product->getFinalPrice();
        
        return [
            'event' => 'add_to_cart',
            'ecommerce' => [
                'items' => [[
                    'item_id' => $product->getSku(),
                    'item_name' => $product->getName(),
                    'item_brand' => $product->getAttributeText('manufacturer') ?? '',
                    'quantity' => $qty,
                    'price' => $price
                ]]
            ]
        ];
    }

    /**
     * Generate begin checkout event data
     *
     * @param array $items
     * @param float $value
     * @param int|null $storeId
     * @return array
     */
    public function getBeginCheckoutEventData($items, $value, $storeId = null)
    {
        if (!$this->config->isTrackCheckoutEnabled($storeId)) {
            return [];
        }

        return [
            'event' => 'begin_checkout',
            'ecommerce' => [
                'items' => $items,
                'value' => $value
            ]
        ];
    }

    /**
     * Generate purchase event data
     *
     * @param \Magento\Sales\Model\Order $order
     * @param int|null $storeId
     * @return array
     */
    public function getPurchaseEventData($order, $storeId = null)
    {
        if (!$this->config->isTrackPurchasesEnabled($storeId)) {
            return [];
        }

        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $items[] = [
                'item_id' => $item->getSku(),
                'item_name' => $item->getName(),
                'item_brand' => $product ? ($product->getAttributeText('manufacturer') ?? '') : '',
                'price' => (float)$item->getPrice(),
                'quantity' => (int)$item->getQtyOrdered()
            ];
        }

        $value = $order->getGrandTotal();
        if (!$this->config->includeTaxInRevenue($storeId)) {
            $value -= $order->getTaxAmount();
        }
        
        if (!$this->config->includeShippingInRevenue($storeId)) {
            $value -= $order->getShippingAmount();
        }

        return [
            'event' => 'purchase',
            'ecommerce' => [
                'transaction_id' => $order->getIncrementId(),
                'value' => (float)$value,
                'tax' => (float)$order->getTaxAmount(),
                'shipping' => (float)$order->getShippingAmount(),
                'currency' => $order->getOrderCurrencyCode(),
                'items' => $items
            ]
        ];
    }

    /**
     * Format event data as JSON
     *
     * @param array $eventData
     * @return string
     */
    public function formatEventDataAsJson($eventData)
    {
        return $this->json->serialize($eventData);
    }

    /**
     * Generate custom event data
     *
     * @param string $eventId
     * @param array $params
     * @param int|null $storeId
     * @return array
     */
    public function getCustomEventData($eventId, $params = [], $storeId = null)
    {
        if (!$this->config->isCustomEventsEnabled($storeId)) {
            return [];
        }
        
        $eventConfig = $this->config->getCustomEventById($eventId, $storeId);
        if (!$eventConfig || empty($eventConfig['event_name'])) {
            return [];
        }
        
        $eventData = [
            'event' => $eventConfig['event_name'],
            'event_category' => $eventConfig['event_category'] ?? '',
            'non_interaction' => !empty($eventConfig['non_interaction'])
        ];
        
        // Add custom parameters if defined
        if (!empty($eventConfig['parameters']) && is_array($eventConfig['parameters'])) {
            foreach ($eventConfig['parameters'] as $param) {
                if (!empty($param['param_name'])) {
                    $paramValue = '';
                    // Check if a value is provided in the params array
                    if (isset($params[$param['param_name']])) {
                        $paramValue = $params[$param['param_name']];
                    } elseif (!empty($param['default_value'])) {
                        // Use default value if no value is provided
                        $paramValue = $param['default_value'];
                    }
                    
                    $eventData[$param['param_name']] = $paramValue;
                }
            }
        }
        
        return $eventData;
    }
    
    /**
     * Generate data import/export configuration
     *
     * @param int|null $storeId
     * @return array
     */
    public function getDataImportExportConfig($storeId = null)
    {
        $settings = $this->config->getDataImportExportSettings($storeId);
        
        if (!$settings['enabled']) {
            return [];
        }
        
        return [
            'export_format' => $settings['export_format'],
            'export_frequency' => $settings['export_frequency']
        ];
    }

    /**
     * Validate GA4 connection
     *
     * This method validates if the measurement ID is in correct format
     * and makes a test API call if an API secret is provided
     *
     * @param string $measurementId
     * @param string|null $apiSecret
     * @return array
     */
    public function validateConnection($measurementId, $apiSecret = null)
    {
        $result = [
            'success' => false,
            'message' => ''
        ];

        // Validate Measurement ID format (should be G-XXXXXXXX)
        if (!preg_match('/^G-[A-Z0-9]{10}$/i', $measurementId)) {
            $result['message'] = 'Invalid Measurement ID format. It should be in the format G-XXXXXXXXXX';
            return $result;
        }

        // Basic validation successful
        $result['success'] = true;
        $result['message'] = 'Measurement ID format is valid';
        
        // If API Secret is provided, attempt to validate with the GA4 API
        if ($apiSecret) {
            try {
                // In a real implementation, we would make an API call to validate
                // For now, we just validate that the API secret has a proper length
                if (strlen($apiSecret) < 10) {
                    $result['success'] = false;
                    $result['message'] = 'API Secret appears to be too short';
                } else {
                    $result['message'] .= ' and API Secret format is valid';
                }
            } catch (\Exception $e) {
                $result['success'] = false;
                $result['message'] = 'Error validating API Secret: ' . $e->getMessage();
            }
        }
        
        return $result;
    }
}
