<?php
/**
 * GA4Pro Analytics Checkout Observer
 */
namespace Magento\GA4Pro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;
use Magento\Framework\Registry;

class Checkout implements ObserverInterface
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
        if (!$this->config->isTrackCheckoutEnabled()) {
            return;
        }

        $quote = $this->checkoutSession->getQuote();
        if (!$quote || !$quote->getId()) {
            return;
        }

        $items = [];
        $value = 0;

        foreach ($quote->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $items[] = [
                'item_id' => $item->getSku(),
                'item_name' => $item->getName(),
                'item_brand' => $product ? ($product->getAttributeText('manufacturer') ?? '') : '',
                'price' => (float)$item->getPrice(),
                'quantity' => (int)$item->getQty()
            ];

            $value += $item->getPrice() * $item->getQty();
        }

        // Store checkout data in registry for the frontend script to use
        $eventData = $this->ga4->getBeginCheckoutEventData($items, $value);
        
        if (!empty($eventData)) {
            $this->registry->register('ga4pro_checkout_event', $eventData, true);
        }
    }
}
