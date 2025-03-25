<?php
/**
 * GA4Pro Analytics Add to Cart Observer
 */
namespace Magento\GA4Pro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\GA4Pro\Model\Config;
use Magento\GA4Pro\Model\GA4;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\Registry;

class AddToCart implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

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
     * @var Json
     */
    protected $json;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param RequestInterface $request
     * @param CheckoutSession $checkoutSession
     * @param Config $config
     * @param GA4 $ga4
     * @param Json $json
     * @param LayoutInterface $layout
     * @param Registry $registry
     */
    public function __construct(
        RequestInterface $request,
        CheckoutSession $checkoutSession,
        Config $config,
        GA4 $ga4,
        Json $json,
        LayoutInterface $layout,
        Registry $registry
    ) {
        $this->request = $request;
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->ga4 = $ga4;
        $this->json = $json;
        $this->layout = $layout;
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
        if (!$this->config->isTrackAddToCartEnabled()) {
            return;
        }

        $product = $observer->getEvent()->getProduct();
        if (!$product) {
            return;
        }

        $qty = $this->request->getParam('qty', 1);

        // Store add to cart data in registry for the frontend script to use
        $eventData = $this->ga4->getAddToCartEventData($product, $qty);
        
        if (!empty($eventData)) {
            $this->registry->register('ga4pro_add_to_cart_event', $eventData, true);
        }
    }
}
