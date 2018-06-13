<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class CustomerSocialIdentityRepository
 *
 * @package UmberFirm\Bundle\CustomerBundle\Repository
 */
class CustomerSocialIdentityRepository extends EntityRepository
{
    /**
     * @param SocialNetwork $socialNetwork
     * @param string $socialId
     *
     * @return null|object|CustomerSocialIdentity
     */
    public function findSocialIdentity(SocialNetwork $socialNetwork, string $socialId): ?CustomerSocialIdentity
    {
        return $this->findOneBy(
            [
                'socialId' => $socialId,
                'socialNetwork' => $socialNetwork->getId()->toString(),
            ]
        );
    }
}
