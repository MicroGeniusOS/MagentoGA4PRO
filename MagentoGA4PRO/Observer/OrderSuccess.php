<?php
/**
 * GA4Pro Analytics Order Success Observer
 */
namespace Magento\GA4Pro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;
use Magento\Framework\Registry;

class OrderSuccess implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

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
     * @param CheckoutSession $checkoutSession
     * @param Config $config
     * @param GA4 $ga4
     * @param Registry $registry
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Config $config,
        GA4 $ga4,
        Registry $registry
    ) {
        $this->checkoutSession = $checkoutSession;
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
        if (!$this->config->isTrackPurchasesEnabled()) {
            return;
        }

        $order = $this->checkoutSession->getLastRealOrder();
        if (!$order || !$order->getId()) {
            return;
        }

        // Store purchase data in registry for the frontend script to use
        $eventData = $this->ga4->getPurchaseEventData($order);
        
        if (!empty($eventData)) {
            $this->registry->register('ga4pro_purchase_event', $eventData, true);
        }
    }
}
