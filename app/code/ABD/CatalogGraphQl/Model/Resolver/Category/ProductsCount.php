<?php

declare(strict_types=1);

namespace ABD\CatalogGraphQl\Model\Resolver\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Smile\ElasticsuiteCatalog\Model\ResourceModel\Product\Fulltext\CollectionFactory as ProductCollectionFactory;

/**
 * Retrieves products count for a category
 */
class ProductsCount implements ResolverInterface
{
    /**
     * @var ProductCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var string
     */
    private $searchRequestName;

    /**
     * @var Visibility
     */
    private $catalogProductVisibility;

    /**
     * ProductsCount constructor.
     * @param Visibility $catalogProductVisibility
     * @param ProductCollectionFactory $collectionFactory
     * @param string $searchRequestName
     */
    public function __construct(
        Visibility $catalogProductVisibility,
        ProductCollectionFactory $collectionFactory,
        string $searchRequestName = 'catalog_view_container'
    ) {
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->collectionFactory = $collectionFactory;
        $this->searchRequestName = $searchRequestName;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value should be specified'));
        }

        /** @var Category $category */
        $category = $value['model'];
        $productCollection = $this->collectionFactory->create(['searchRequestName' => $this->searchRequestName])
            ->addCategoryFilter($category)
            ->setStoreId($category->getStoreId())
            ->setVisibility($this->catalogProductVisibility->getVisibleInSiteIds());
        $size = $productCollection->getSize();

        return $size;
    }
}
