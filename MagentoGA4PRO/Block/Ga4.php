<?php
/**
 * GA4Pro Analytics Block
 */
namespace Magento\GA4Pro\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;

class Ga4 extends Template
{
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var GA4
     */
    protected $ga4Model;
    
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * @var Json
     */
    protected $json;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * @param Context $context
     * @param Config $config
     * @param GA4 $ga4Model
     * @param StoreManagerInterface $storeManager
     * @param Json $json
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        GA4 $ga4Model,
        StoreManagerInterface $storeManager,
        Json $json,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->config = $config;
        $this->ga4Model = $ga4Model;
        $this->storeManager = $storeManager;
        $this->json = $json;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    
    /**
     * Check if GA4 tracking is enabled
     *
     * @return bool
     */
    public function isGa4Enabled()
    {
        return $this->config->isEnabled();
    }
    
    /**
     * Get GA4 measurement ID
     *
     * @return string
     */
    public function getMeasurementId()
    {
        return $this->config->getMeasurementId();
    }
    
    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->config->isDebugMode();
    }
    
    /**
     * Check if page view tracking is enabled
     *
     * @return bool
     */
    public function isTrackPageViewsEnabled()
    {
        return $this->config->isTrackPageViewsEnabled();
    }
    
    /**
     * Get store currency code
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        try {
            return $this->storeManager->getStore()->getCurrentCurrencyCode();
        } catch (\Exception $e) {
            return 'USD';
        }
    }
    
    /**
     * Get customer data for tracking, if available and allowed
     *
     * @return string|null
     */
    public function getCustomerData()
    {
        if (!$this->config->isEnhancedEcommerceEnabled()) {
            return null;
        }
        
        try {
            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                
                $data = [
                    'customer_id' => $customer->getId(),
                    'customer_group' => $customer->getGroupId(),
                ];
                
                return $this->json->serialize($data);
            }
        } catch (\Exception $e) {
            // Silently fail if we can't get customer data
        }
        
        return null;
    }
    
    /**
     * Check if pending command queue is enabled
     *
     * @return bool
     */
    public function isPendingQueueEnabled()
    {
        return true;
    }
}