<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Event\Order\Subscriber;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository;
use UmberFirm\Bundle\PublicBundle\Component\Order\DataTransferObject\Factory\OrderDataTransferObjectFactoryInterface;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\SignUp\CustomerSignUpManagerInterface;
use UmberFirm\Bundle\PublicBundle\Component\Order\Producer\FastOrderProducerInterface;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEvent;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FastOrderSubscriber
 *
 * @package UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber
 */
class FastOrderSubscriber implements FastOrderSubscriberInterface
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var CustomerSignUpManagerInterface
     */
    private $customerSignUpManager;

    /**
     * @var FastOrderProducerInterface
     */
    private $fastOrderProducer;

    /**
     * @var OrderDataTransferObjectFactoryInterface
     */
    private $orderDataTransferObjectFactory;

    /**
     * @var FastOrderEvent
     */
    private $fastOrderEvent;

    /**
     * FastOrderSubscriber constructor.
     *
     * @param CustomerRepository $customerRepository
     * @param CustomerSignUpManagerInterface $customerSignUpManager
     * @param OrderDataTransferObjectFactoryInterface $orderDataTransferObjectFactory
     * @param FastOrderProducerInterface $fastOrderProducer
     */
    public function __construct(
        CustomerRepository $customerRepository,
        CustomerSignUpManagerInterface $customerSignUpManager,
        OrderDataTransferObjectFactoryInterface $orderDataTransferObjectFactory,
        FastOrderProducerInterface $fastOrderProducer
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerSignUpManager = $customerSignUpManager;
        $this->fastOrderProducer = $fastOrderProducer;
        $this->orderDataTransferObjectFactory = $orderDataTransferObjectFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FastOrderEvent::PLACEMENT => 'onPlacement',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onPlacement(FastOrderEventInterface $fastOrderEvent): void
    {
        $this->fastOrderEvent = $fastOrderEvent;

        $phone = $fastOrderEvent->getFastOrder()->getPhone();
        $shop = $fastOrderEvent->getShop();

        $customer = $fastOrderEvent->getCustomer() ?? $this->loadCustomer($phone, $shop);

        $fastOrderDTO = $this->orderDataTransferObjectFactory
            ->createFastOrderDTO($fastOrderEvent->getFastOrder(), $shop, $customer);
        $this->fastOrderProducer->save($fastOrderDTO);
    }

    /**
     * @param string $phone
     * @param Shop $shop
     *
     * @return Customer
     */
    private function loadCustomer(string $phone, Shop $shop): Customer
    {
        $customer = $this->customerRepository->loadCustomerByPhone($phone, $shop);
        $email = $this->fastOrderEvent->getFastOrder()->getEmail();

        if (null === $customer) {
            $customer = new Customer();
            $customer->setPhone($phone);
            $customer->setShop($shop);
            $customer->setEmail($email);

            $this->customerSignUpManager->complete($customer);
        }

        if (true === empty($customer->getEmail())) {
            $customer = $this->customerSignUpManager->addEmail($customer, $email);
        }

        return $customer;
    }
}
