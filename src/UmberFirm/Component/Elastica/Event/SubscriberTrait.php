<?php

declare(strict_types=1);

namespace UmberFirm\Component\Elastica\Event;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SubscriberTrait
 *
 * @package UmberFirm\Component\Elastica\Event
 */
trait SubscriberTrait
{
    /**
     * @var IndexableInterface
     */
    private $indexable;

    /**
     * @var array
     */
    private $config;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var ObjectPersisterInterface
     */
    protected $objectPersister;

    /**
     * {@inheritdoc}
     */
    public function isObjectIndexable(UuidEntityInterface $entity): bool
    {
        $index = $this->config['indexName']??$this->config['index'];
        $type = $this->config['typeName']??$this->config['type'];

        return (bool) $this->indexable->isObjectIndexable($index, $type, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceOne(UuidEntityInterface $entity): void
    {
        if (true === $this->isObjectPersistence($entity)) {
            $this->objectPersister->replaceOne($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isObjectPersistence(UuidEntityInterface $entity): bool
    {
        return true === $this->objectPersister->handlesObject($entity) && true === $this->isObjectIndexable($entity);
    }
}
