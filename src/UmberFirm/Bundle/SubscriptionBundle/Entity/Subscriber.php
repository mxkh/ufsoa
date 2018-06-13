<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Subscriber
 *
 * @package UmberFirm\Bundle\SubscriptionBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\SubscriptionBundle\Repository\SubscriberRepository")
 */
class Subscriber implements UuidEntityInterface
{
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param null|string $email
     *
     * @return Subscriber
     */
    public function setEmail(?string $email): Subscriber
    {
        $this->email = $email;

        return $this;
    }
}
