<?php
/**
 * class  ProductSalableQty
 *
 * @package      TigerOne
 * @description  ProductSalableQty class  to get SalableQty of an product
 * @copyright    2022 Codilar Technologies Pvt. Ltd. . All rights reserved.
 *
 */
declare(strict_types=1);

namespace HookahShisha\CatalogGraphQl\Model\Resolver\UrlRewrite;

use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Psr\Log\LoggerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 *ProductSalableQty  Resolver to get SalableQty of an product
 */
class Saleableqty implements ResolverInterface
{
    const SIMPLE_PRODUCT = 'simple';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var StockRegistryInterface
     */

    private $stockRegistry;
    /**
     * @var ProductInterface
     */
    private $product;


    /**
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param StockRegistryInterface $stockRegistry
     * @param ProductInterface $product
     */
    public function __construct(
        StoreManagerInterface         $storeManager,
        LoggerInterface               $logger,
        StockRegistryInterface $stockRegistry,
        ProductInterface $product    )
    {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->stockRegistry = $stockRegistry;
        $this->product = $product;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return int
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        $product = $value['model'];
        $stockItem = $this->stockRegistry->getStockItem($product->getId());
        $quantity = $stockItem->getQty();
        return $quantity??0;

    }
}
