<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CityStreetAwareInterface
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 */
interface CityStreetAwareInterface
{
    /**
     * @return Collection|Street[]
     */
    public function getStreets(): Collection;

    /**
     * @param Street $street
     *
     * @return City
     */
    public function addStreet(Street $street): City;

    /**
     * @param Street $street
     *
     * @return City
     */
    public function removeStreet(Street $street): City;
}
