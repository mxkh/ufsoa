<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CityBranchAwareInterface
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 */
interface CityBranchAwareInterface
{
    /**
     * @return Collection|Branch[]
     */
    public function getBranches(): Collection;

    /**
     * @param Branch $branch
     *
     * @return City
     */
    public function addBranch(Branch $branch): City;

    /**
     * @param Branch $branch
     *
     * @return City
     */
    public function removeBranch(Branch $branch): City;
}
