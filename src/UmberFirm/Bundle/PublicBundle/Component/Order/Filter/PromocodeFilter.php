<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Filter;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;

/**
 * Class PromocodeFilter
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Filter
 */
class PromocodeFilter extends AbstractPromocodeFilter implements PromocodeFilterInterface
{
    /**
     * @var Promocode
     */
    private $promocode;

    /**
     * @var null|Customer
     */
    private $customer;

    /**
     * PromocodeFilter constructor.
     *
     * @param Promocode $promocode
     * @param null|Customer $customer
     */
    public function __construct(Promocode $promocode, ?Customer $customer)
    {
        $this->promocode = $promocode;
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function customer(): PromocodeFilterInterface
    {
        if (null === $this->promocode->getCustomer()) {
            return $this;
        }

        if (null === $this->customer) {
            $this->result = false;

            return $this;
        }

        if ($this->promocode->getCustomer()->getId()->toString() !== $this->customer->getId()->toString()) {
            $this->result = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function start(): PromocodeFilterInterface
    {
        if (null === $this->promocode->getStart()) {
            return $this;
        }

        if (new \DateTime() < $this->promocode->getStart()) {
            $this->result = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function finish(): PromocodeFilterInterface
    {
        if (null === $this->promocode->getFinish()) {
            return $this;
        }

        if (new \DateTime() > $this->promocode->getFinish()) {
            $this->result = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reusable(): PromocodeFilterInterface
    {
        if (true === $this->promocode->getIsReusable()) {
            return $this;
        }

        if (1 === $this->promocode->getUsed()) {
            $this->result = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function limiting(): PromocodeFilterInterface
    {
        if (0 === $this->promocode->getLimiting()) {
            return $this;
        }

        if ($this->promocode->getLimiting() === $this->promocode->getUsed()) {
            $this->result = false;
        }

        return $this;
    }
}
