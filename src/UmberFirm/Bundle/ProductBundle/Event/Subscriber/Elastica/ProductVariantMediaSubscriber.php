<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Event\Subscriber\Elastica;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Doctrine\Listener;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariantMedia;
use UmberFirm\Component\Elastica\Event\SubscriberInterface;
use UmberFirm\Component\Elastica\Event\SubscriberTrait;

/**
 * Class ProductVariantMediaSubscriber
 *
 * @package UmberFirm\Bundle\ProductBundle\Event\Subscriber\Elastica
 */
final class ProductVariantMediaSubscriber extends Listener implements EventSubscriber, SubscriberInterface
{
    use SubscriberTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ObjectPersisterInterface $objectPersister,
        IndexableInterface $indexable,
        array $config = []
    )
    {
        parent::__construct($objectPersister, $indexable, $config);

        $this->objectPersister = $objectPersister;
        $this->indexable = $indexable;
        $this->config = $config;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        /** @var ProductVariantMedia $entity */
        $entity = $eventArgs->getObject();

        if (false === ($entity instanceof ProductVariantMedia)) {
            return;
        }

        parent::postPersist($eventArgs);

        $this->replaceOne($entity->getProductMedia()->getProduct());
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        /** @var ProductVariantMedia $entity */
        $entity = $eventArgs->getObject();

        if (false === ($entity instanceof ProductVariantMedia)) {
            return;
        }

        parent::preRemove($eventArgs);

        $productVariant = $entity->getProductVariant();
        $productVariant->removeMedia($entity);

        $this->replaceOne($productVariant->getProduct());
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::preRemove,
        ];
    }
}
