<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Filter;

/**
 * Interface PromocodeFilterInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Filter
 */
interface PromocodeFilterInterface
{
    /**
     * @return PromocodeFilterInterface
     */
    public function customer(): PromocodeFilterInterface;

    /**
     * @return PromocodeFilterInterface
     */
    public function start(): PromocodeFilterInterface;

    /**
     * @return PromocodeFilterInterface
     */
    public function finish(): PromocodeFilterInterface;

    /**
     * @return PromocodeFilterInterface
     */
    public function reusable(): PromocodeFilterInterface;

    /**
     * @return PromocodeFilterInterface
     */
    public function limiting(): PromocodeFilterInterface;

    /**
     * @return bool
     */
    public function getResult(): bool;
}
