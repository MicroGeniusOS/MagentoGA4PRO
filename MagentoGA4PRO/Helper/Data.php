<?php
/**
 * GA4Pro Analytics Helper
 */
namespace Magento\GA4Pro\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\GA4Pro\Model\Config;

class Data extends AbstractHelper
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
     * @param Context $context
     * @param Config $config
     * @param Json $json
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $json
    ) {
        $this->config = $config;
        $this->json = $json;
        parent::__construct($context);
    }

    /**
     * Check if GA4 tracking is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return $this->config->isEnabled($storeId);
    }

    /**
     * Get GA4 measurement ID
     *
     * @param mixed $storeId
     * @return string
     */
    public function getMeasurementId($storeId = null)
    {
        return $this->config->getMeasurementId($storeId);
    }

    /**
     * Format product data for GA4 tracking
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function formatProductData($product)
    {
        $categoryNames = [];
        
        $categoryCollection = $product->getCategoryCollection();
        foreach ($categoryCollection as $category) {
            $categoryNames[] = $category->getName();
        }
        
        return [
            'item_id' => $product->getSku(),
            'item_name' => $product->getName(),
            'item_brand' => $product->getAttributeText('manufacturer') ?? '',
            'item_category' => !empty($categoryNames) ? $categoryNames[0] : '',
            'price' => $product->getFinalPrice()
        ];
    }

    /**
     * Convert product data array to JSON
     *
     * @param array $data
     * @return string
     */
    public function convertToJson($data)
    {
        return $this->json->serialize($data);
    }

    /**
     * Check if debug mode is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function isDebugMode($storeId = null)
    {
        return $this->config->isDebugMode($storeId);
    }
}
