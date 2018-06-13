<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface DefaultableInterface
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 */
interface DefaultableInterface
{
    /**
     * @return bool
     */
    public function getIsDefault();

    /**
     * @param bool $isDefault
     *
     * @return $this
     */
    public function setIsDefault(bool $isDefault);

    /**
     * @return Collection|object[]
     */
    public function getShopDefaultables();
}
