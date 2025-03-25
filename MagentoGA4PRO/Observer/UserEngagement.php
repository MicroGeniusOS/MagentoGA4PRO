<?php
/**
 * GA4Pro Analytics User Engagement Observer
 */
namespace Magento\GA4Pro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\GA4Pro\Model\Config;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\StoreManagerInterface;

class UserEngagement implements ObserverInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Config $config
     * @param JsonHelper $jsonHelper
     * @param Registry $registry
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $config,
        JsonHelper $jsonHelper,
        Registry $registry,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->jsonHelper = $jsonHelper;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * Track user engagement events
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $storeId = $this->storeManager->getStore()->getId();
        
        if (!$this->config->isEnabled($storeId) || !$this->config->isEnhancedEcommerceEnabled($storeId)) {
            return;
        }

        // Get the event type from observer
        $eventType = $observer->getEvent()->getName();
        $product = $observer->getEvent()->getProduct();
        
        if (!$product) {
            return;
        }
        
        // Track based on customer segment if available
        $customerSegment = $this->getCustomerSegment();
        
        // Store engagement data in registry for frontend JS to pick up
        $engagementData = [
            'event' => 'user_engagement',
            'engagement_type' => $this->mapEventToEngagementType($eventType),
            'customer_segment' => $customerSegment,
            'product_data' => [
                'item_id' => $product->getSku(),
                'item_name' => $product->getName()
            ]
        ];
        
        // Store the engagement data in registry for the frontend to access
        $existingData = $this->registry->registry('ga4pro_user_engagement_data') ?: [];
        $existingData[] = $engagementData;
        
        // Remove previous registry value if it exists
        if ($this->registry->registry('ga4pro_user_engagement_data')) {
            $this->registry->unregister('ga4pro_user_engagement_data');
        }
        
        // Register new value
        $this->registry->register('ga4pro_user_engagement_data', $existingData);
    }
    
    /**
     * Get customer segment information
     *
     * @return string
     */
    private function getCustomerSegment()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return 'guest';
        }
        
        $customer = $this->customerSession->getCustomer();
        
        // Determine customer segment based on order history or customer group
        $customerGroup = $customer->getGroupId();
        
        // Map customer group to a segment
        $segments = [
            1 => 'general',
            2 => 'wholesale',
            3 => 'retailer'
            // Add more mappings as needed
        ];
        
        return isset($segments[$customerGroup]) ? $segments[$customerGroup] : 'other';
    }
    
    /**
     * Map Magento event type to GA4 engagement type
     *
     * @param string $eventType
     * @return string
     */
    private function mapEventToEngagementType($eventType)
    {
        $map = [
            'catalog_product_save_after' => 'product_update',
            'sales_quote_save_after' => 'cart_update',
            'wishlist_add_product' => 'wishlist_add',
            'review_save_after' => 'product_review',
            'catalog_product_compare_add_product' => 'product_compare'
        ];
        
        return isset($map[$eventType]) ? $map[$eventType] : 'other_engagement';
    }
}