<?php
/**
 * GA4Pro Analytics Configuration Model
 */
namespace Magento\GA4Pro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var Json
     */
    protected $json;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $json
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $json
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
    }

    /**
     * Check if GA4 tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/general/enabled',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get GA4 Measurement ID
     *
     * @param mixed $storeId
     * @return string
     */
    public function getMeasurementId($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'magento_ga4pro/general/measurement_id',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get API Secret
     *
     * @param mixed $storeId
     * @return string
     */
    public function getApiSecret($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'magento_ga4pro/general/api_secret',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if debug mode is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isDebugMode($storeId = null)
    {
        return (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/general/debug_mode',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if page view tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isTrackPageViewsEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/events/track_pageviews',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if product view tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isTrackProductViewsEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/events/track_product_views',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if add to cart tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isTrackAddToCartEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/events/track_add_to_cart',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if checkout tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isTrackCheckoutEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/events/track_checkout',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if purchase tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isTrackPurchasesEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/events/track_purchases',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if enhanced ecommerce is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isEnhancedEcommerceEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/enhanced/enable_enhanced',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if custom event tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isCustomEventsEnabled($storeId = null)
    {
        return $this->isEnabled($storeId) && (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/custom_events/enable_custom_events',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get custom events configuration
     *
     * @param mixed $storeId
     * @return array
     */
    public function getCustomEvents($storeId = null)
    {
        $eventsJson = $this->scopeConfig->getValue(
            'magento_ga4pro/custom_events/custom_events_config',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        if (empty($eventsJson)) {
            return [];
        }
        
        try {
            $events = $this->json->unserialize($eventsJson);
            return is_array($events) ? $events : [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get raw custom events configuration JSON
     *
     * @param mixed $storeId
     * @return string
     */
    public function getCustomEventsConfig($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'magento_ga4pro/custom_events/custom_events_config',
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: '[]';
    }

    /**
     * Get custom event by ID
     *
     * @param string $eventId
     * @param mixed $storeId
     * @return array|null
     */
    public function getCustomEventById($eventId, $storeId = null)
    {
        $events = $this->getCustomEvents($storeId);
        
        foreach ($events as $event) {
            if (isset($event['event_id']) && $event['event_id'] === $eventId) {
                return $event;
            }
        }
        
        return null;
    }

    /**
     * Check if tax should be included in revenue
     *
     * @param mixed $storeId
     * @return bool
     */
    public function includeTaxInRevenue($storeId = null)
    {
        return (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/enhanced/include_tax',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if shipping should be included in revenue
     *
     * @param mixed $storeId
     * @return bool
     */
    public function includeShippingInRevenue($storeId = null)
    {
        return (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/enhanced/include_shipping',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    
    /**
     * Get data import/export settings
     *
     * @param mixed $storeId
     * @return array
     */
    public function getDataImportExportSettings($storeId = null)
    {
        $enabled = (bool) $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/enable_import_export',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        $exportFormat = $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/export_format',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        $exportFrequency = $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/export_frequency',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        return [
            'enabled' => $enabled,
            'export_format' => $exportFormat ?: 'json',
            'export_frequency' => $exportFrequency ?: 'daily'
        ];
    }
}
