<?php
/**
 * GA4Pro Analytics Dashboard Overview Block
 */
namespace Magento\GA4Pro\Block\Adminhtml\Dashboard;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;
use Magento\GA4Pro\Helper\SystemConfigWorkaround;

class Overview extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var Config
     */
    protected $ga4Config;
    
    /**
     * @var GA4
     */
    protected $ga4Model;
    
    /**
     * @var Curl
     */
    protected $curl;
    
    /**
     * @var Json
     */
    protected $json;
    
    /**
     * @var SystemConfigWorkaround
     */
    protected $configHelper;
    
    /**
     * @param Context $context
     * @param Config $ga4Config
     * @param GA4 $ga4Model
     * @param Curl $curl
     * @param Json $json
     * @param SystemConfigWorkaround $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $ga4Config,
        GA4 $ga4Model,
        Curl $curl,
        Json $json,
        SystemConfigWorkaround $configHelper,
        array $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->ga4Config = $ga4Config;
        $this->ga4Model = $ga4Model;
        $this->curl = $curl;
        $this->json = $json;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }
    
    /**
     * Check if GA4 is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return $this->ga4Config->isEnabled() && $this->ga4Config->getMeasurementId();
    }
    
    /**
     * Get GA4 tracking status information
     *
     * @return array
     */
    public function getTrackingStatus()
    {
        $status = [
            'enabled' => $this->ga4Config->isEnabled(),
            'measurement_id' => $this->ga4Config->getMeasurementId(),
            'api_secret' => !empty($this->ga4Config->getApiSecret()),
            'enhanced_ecommerce' => $this->ga4Config->isEnhancedEcommerceEnabled(),
            'tracked_events' => $this->getEnabledEvents()
        ];
        
        return $status;
    }
    
    /**
     * Get list of enabled events
     *
     * @return array
     */
    public function getEnabledEvents()
    {
        $events = [];
        
        if ($this->ga4Config->isTrackPageViewsEnabled()) {
            $events[] = __('Page Views');
        }
        
        if ($this->ga4Config->isTrackProductViewsEnabled()) {
            $events[] = __('Product Views');
        }
        
        if ($this->ga4Config->isTrackAddToCartEnabled()) {
            $events[] = __('Add to Cart');
        }
        
        if ($this->ga4Config->isTrackCheckoutEnabled()) {
            $events[] = __('Checkout Steps');
        }
        
        if ($this->ga4Config->isTrackPurchasesEnabled()) {
            $events[] = __('Purchases');
        }
        
        // Get custom events if enabled
        if ($this->ga4Config->isCustomEventsEnabled()) {
            $customEventsConfig = $this->ga4Config->getCustomEventsConfig();
            if (!empty($customEventsConfig)) {
                try {
                    $customEvents = $this->json->unserialize($customEventsConfig);
                    if (is_array($customEvents)) {
                        foreach ($customEvents as $event) {
                            if (isset($event['name'])) {
                                $events[] = $event['name'];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Unable to parse custom events JSON
                }
            }
        }
        
        return $events;
    }
    
    /**
     * Get Google Analytics 4 dashboard URL
     *
     * @return string
     */
    public function getGa4DashboardUrl()
    {
        $measurementId = $this->ga4Config->getMeasurementId();
        
        // Extract GA4 property ID from measurement ID (G-XXXXXXXX)
        $propertyId = str_replace('G-', '', $measurementId);
        
        // Return URL to GA4 reporting
        return 'https://analytics.google.com/analytics/web/#/p' . $propertyId . '/reports';
    }
    
    /**
     * Get configuration tips
     *
     * @return array
     */
    public function getConfigurationTips()
    {
        $tips = [];
        
        if (!$this->ga4Config->isEnabled()) {
            $tips[] = __('Enable GA4 tracking in the module configuration.');
        }
        
        if (empty($this->ga4Config->getMeasurementId())) {
            $tips[] = __('Add your Google Analytics 4 Measurement ID (starts with G-).');
        }
        
        if (empty($this->ga4Config->getApiSecret())) {
            $tips[] = __('For enhanced security and server-side tracking, add your GA4 API Secret.');
        }
        
        if (!$this->ga4Config->isEnhancedEcommerceEnabled()) {
            $tips[] = __('Enable Enhanced E-commerce to track detailed shopping behavior.');
        }
        
        return $tips;
    }
    
    /**
     * Get GA4 real-time user count (if API key is configured)
     * This is a placeholder method that would be implemented with real API access
     *
     * @return int|null
     */
    public function getRealTimeUsers()
    {
        // This would require Google Analytics API key and OAuth2 authentication
        // For now, return null as it's beyond the scope of this implementation
        return null;
    }
    
    /**
     * Check if export functionality is enabled
     *
     * @return bool
     */
    public function isExportEnabled()
    {
        return $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/enable_import_export',
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get default export format
     *
     * @return string
     */
    public function getDefaultExportFormat()
    {
        return $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/export_format',
            ScopeInterface::SCOPE_STORE
        ) ?: 'json';
    }
}