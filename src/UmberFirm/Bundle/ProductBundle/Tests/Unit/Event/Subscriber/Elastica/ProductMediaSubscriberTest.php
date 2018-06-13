<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Unit\Event\Subscriber\Elastica;

use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductMedia;
use UmberFirm\Bundle\ProductBundle\Event\Subscriber\Elastica\ProductMediaSubscriber;

/**
 * Class ProductMediaSubscriberTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Unit\Event\Subscriber\Elastica
 */
class ProductMediaSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ObjectPersisterInterface
     */
    private $objectPersister;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|IndexableInterface
     */
    private $indexable;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|LifecycleEventArgs
     */
    private $lifecycleEventArgs;

    /**
     * @var array
     */
    private $config = ['indexName' => 'umberfirm', 'typeName' => 'product'];

    protected function setUp()
    {
        $this->objectPersister = $this->createMock(ObjectPersisterInterface::class);
        $this->objectPersister
            ->expects($this->any())
            ->method('handlesObject')
            ->willReturn(true);
        $this->objectPersister
            ->expects($this->any())
            ->method('replaceOne');

        $this->indexable = $this->createMock(IndexableInterface::class);
        $this->indexable
            ->expects($this->any())
            ->method('isObjectIndexable')
            ->willReturn(true);

        $this->lifecycleEventArgs = $this->getMockBuilder(LifecycleEventArgs::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityManager', 'getObject'])
            ->getMock();
    }

    public function testPostPersist()
    {
        $entity = new ProductMedia();
        $entity->setProduct(new Product());

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($entity);

        $subscriber = new ProductMediaSubscriber($this->objectPersister, $this->indexable, $this->config);
        $subscriber->postPersist($this->lifecycleEventArgs);
    }

    public function testPostPersistWithWrongEntity()
    {
        $entity = new \stdClass();

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($entity);

        $subscriber = new ProductMediaSubscriber($this->objectPersister, $this->indexable, $this->config);
        $subscriber->postPersist($this->lifecycleEventArgs);
    }

    public function testPreRemove()
    {
        $entity = new ProductMedia();
        $entity->setProduct(new Product());

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($entity);

        $subscriber = new ProductMediaSubscriber($this->objectPersister, $this->indexable, $this->config);
        $subscriber->preRemove($this->lifecycleEventArgs);
    }

    public function testPreRemoveWithWrongEntity()
    {
        $entity = new \stdClass();

        $this->lifecycleEventArgs
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($entity);

        $subscriber = new ProductMediaSubscriber($this->objectPersister, $this->indexable, $this->config);
        $subscriber->preRemove($this->lifecycleEventArgs);
    }

    public function testSubscribedEvents()
    {
        $subscriber = new ProductMediaSubscriber($this->objectPersister, $this->indexable, $this->config);
        $this->assertEquals([Events::postPersist, Events::preRemove], $subscriber->getSubscribedEvents());
    }
}
