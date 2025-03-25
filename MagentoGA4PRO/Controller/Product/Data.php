<?php
/**
 * GA4Pro Analytics Product Data Controller
 */
namespace Magento\GA4Pro\Controller\Product;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class Data extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        
        try {
            $productId = $this->getRequest()->getParam('id');
            
            if (!$productId) {
                throw new \InvalidArgumentException('Product ID is required');
            }
            
            $product = $this->productRepository->getById($productId);
            
            // Return basic product data for GA4 tracking
            $data = [
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getFinalPrice(),
                'brand' => $product->getAttributeText('manufacturer') ?? '',
                'category' => ''
            ];
            
            // Try to get the first category
            $categoryCollection = $product->getCategoryCollection();
            foreach ($categoryCollection as $category) {
                $data['category'] = $category->getName();
                break;
            }
            
            return $result->setData([
                'success' => true,
                'data' => $data
            ]);
        } catch (NoSuchEntityException $e) {
            return $result->setData([
                'success' => false,
                'message' => 'Product not found'
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}