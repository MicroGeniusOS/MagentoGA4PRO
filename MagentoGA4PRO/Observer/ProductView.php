<?php
/**
 * GA4Pro Analytics Product View Observer
 */
namespace Magento\GA4Pro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;
use Magento\Framework\Registry;

class ProductView implements ObserverInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var GA4
     */
    protected $ga4;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Config $config
     * @param GA4 $ga4
     * @param Registry $registry
     */
    public function __construct(
        Config $config,
        GA4 $ga4,
        Registry $registry
    ) {
        $this->config = $config;
        $this->ga4 = $ga4;
        $this->registry = $registry;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isTrackProductViewsEnabled()) {
            return;
        }

        $product = $observer->getEvent()->getProduct();
        if (!$product) {
            return;
        }

        // Store product view data in registry for the frontend script to use
        $eventData = $this->ga4->getProductViewEventData($product);
        
        if (!empty($eventData)) {
            $this->registry->register('ga4pro_product_view_event', $eventData, true);
        }
    }
}
