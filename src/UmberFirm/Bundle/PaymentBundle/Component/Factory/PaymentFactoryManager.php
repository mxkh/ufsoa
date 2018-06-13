<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Factory;

use Symfony\Component\Routing\RouterInterface;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\WayForPayAdapter;

/**
 * Class PaymentFactoryManager
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Factory
 */
final class PaymentFactoryManager implements PaymentFactoryManagerInterface
{
    /**
     * @var AbstractPaymentFactory[]|array
     */
    private $abstractFactories;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * PaymentFactory constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->abstractFactories = [
            WayForPayAdapter::NAME => WayForPayFactory::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory(string $code): ?AbstractPaymentFactory
    {
        if (false === array_key_exists($code, $this->abstractFactories)) {
            return null;
        }

        return new $this->abstractFactories[$code]($this->router);
    }
}
