<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use Ramsey\Uuid\Uuid;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;

/**
 * Class ProductRepository
 *
 * @package UmberFirm\Bundle\ProductBundle\Repository
 */
class ProductRepository extends EntityRepository implements RepositoryPagenatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSearchQueryBuilder(?string $searchQuery): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('product');
        if (true === empty($searchQuery)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->leftJoin('product.translations', 'translations')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('product.id', ':search'),
                    $queryBuilder->expr()->like('translations.name', ':search'),
                    $queryBuilder->expr()->like('translations.slug', ':search')
                )
            )
            ->setParameter('search', '%'.$searchQuery.'%');
    }

    /**
     * @param string $uuid
     *
     * @return null|object|Product
     */
    public function findOneByUuid(string $uuid): ?Product
    {
        if (false === Uuid::isValid($uuid)) {
            return null;
        }

        return $this->findOneBy(['id' => $uuid]);
    }

    /**
     * @param Shop $shop
     * @param Category $category
     * @param int $offset
     * @param int $limit
     *
     * @return Product[]|array
     */
    public function findByShopInCategory(Shop $shop, Category $category, int $offset, int $limit): array
    {
        $query = $this
            ->createQueryBuilder('product')
            ->innerJoin('product.categories', 'categories')
            ->where('product.shop = :shop_id')
            ->andWhere('categories.id = :category_id')
            ->setParameters(
                [
                    'shop_id' => $shop->getId()->toString(),
                    'category_id' => $category->getId()->toString(),
                ]
            )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Product $product
     *
     * @return int
     */
    public function updateStock(Product $product): int
    {
        $productVariantRepository = $this->_em->getRepository(ProductVariant::class);
        $productStock = $productVariantRepository->findProductStock($product);

        return (int) $this->createQueryBuilder('p')
            ->update()
            ->set('p.price', ':price')
            ->set('p.salePrice', ':salePrice')
            ->set('p.article', ':article')
            ->set('p.outOfStock', ':outOfStock')
            ->where('p.id = :id')
            ->setParameters(
                [
                    'id' => $product->getId()->toString(),
                    'price' => (float) $productStock['price'],
                    'salePrice' => (float) $productStock['salePrice'],
                    'article' => (string) $productStock['article'],
                    'outOfStock' => (int) $productStock['outOfStock'],
                ]
            )
            ->getQuery()
            ->execute();
    }
}
