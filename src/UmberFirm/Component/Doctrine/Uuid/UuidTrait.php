<?php

declare(strict_types=1);

namespace UmberFirm\Component\Doctrine\Uuid;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UuidTrait
 *
 * @package UmberFirm\Component\Doctrine\Uuid
 */
trait UuidTrait
{
    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="id", type="uuid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
