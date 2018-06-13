<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Manager;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\PublicBundle\Component\Order\Factory\PromocodeFilterFactoryInterface;

/**
 * Class PromocodeVerificationManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Service
 */
class PromocodeManager implements PromocodeManagerInterface
{
    /**
     * @var PromocodeFilterFactoryInterface
     */
    private $filterFactory;

    /**
     * PromocodeVerificationManager constructor.
     *
     * @param PromocodeFilterFactoryInterface $filterFactory
     */
    public function __construct(PromocodeFilterFactoryInterface $filterFactory)
    {
        $this->filterFactory = $filterFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(Promocode $promocode, ?Customer $customer): bool
    {
        $filter = $this->filterFactory->createFilter($promocode, $customer);

        return $filter->customer()->start()->finish()->reusable()->limiting()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function calculate(Promocode $promocode, float $amount): float
    {
        $formulaTemplate = $promocode->getPromocodeEnum()->getCalculate();
        $formula = sprintf($formulaTemplate, $amount, $promocode->getValue());

        return (float) eval('return '.$formula.';');
    }

    /**
     * {@inheritdoc}
     */
    public function incrementUsed(Promocode $promocode): Promocode
    {
        return $promocode->used();
    }
}
