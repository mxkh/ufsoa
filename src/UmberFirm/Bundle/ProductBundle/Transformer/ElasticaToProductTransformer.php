<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Transformer;

use Elastica\Result;
use FOS\ElasticaBundle\Doctrine\AbstractElasticaToModelTransformer;
use UmberFirm\Bundle\ProductBundle\Model\Elastica\ProductModel;

/**
 * Class ElasticaToProductTransformer
 *
 * @package UmberFirm\Bundle\ProductBundle\Transformer
 */
final class ElasticaToProductTransformer extends AbstractElasticaToModelTransformer
{
    /**
     * {@inheritdoc}
     */
    protected function findByIdentifiers(array $identifierValues, $hydrate): array
    {
        /**
         * We do not need to find objects in Doctrine
         * Since all the data store in Elasticsearch
         */
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function transform(array $elasticaObjects): array
    {
        //TODO: In future extend this method
        $objects = [];
        /** @var Result $object */
        foreach ($elasticaObjects as $object) {
            $productModel = new ProductModel();
            $objects[] = $productModel
                ->setId((string) $object->getId())
                ->setName((string) $object->getSource()['name'])
                ->setSlug((string) $object->getSource()['slug'])
                ->setArticle((string) $object->getSource()['article'])
                ->setIsOutOfStock((bool) $object->getSource()['is_out_of_stock'])
                ->setIsActive((bool) $object->getSource()['is_active'])
                ->setIsNew((bool) $object->getSource()['is_new'])
                ->setIsPreOrder((bool) $object->getSource()['is_pre_order'])
                ->setIsSale((bool) $object->getSource()['is_sale'])
                ->setIsHidden((bool) $object->getSource()['is_hidden'])
                ->setDescription((string) $object->getSource()['description'])
                ->setShortDescription((string) $object->getSource()['short_description'])
                ->setManufacturer((array) $object->getSource()['manufacturer'])
                ->setSalePrice((float) $object->getSource()['sale_price'])
                ->setPrice((float) $object->getSource()['price'])
                ->setVariants((array) $object->getSource()['search_data'])
                ->setMedias((array) $object->getSource()['medias'])
                ->setCategories((array) $object->getSource()['categories'])
                ->setCreatedAt((array) $object->getSource()['created_at'])
                ->setUpdatedAt((array) $object->getSource()['updated_at']);
        }

        return $objects;
    }
}
