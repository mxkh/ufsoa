<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Language
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\CommonBundle\Repository\LanguageRepository")
 */
class Language implements UuidEntityInterface
{
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * Set code
     *
     * @param null|string $code
     *
     * @return Language
     */
    public function setCode(?string $code): Language
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param null|string $name
     *
     * @return Language
     */
    public function setName(?string $name): Language
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
