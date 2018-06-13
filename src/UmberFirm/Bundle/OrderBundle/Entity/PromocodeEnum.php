<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

/**
 * Class PromocodeEnum
 *
 * @package UmberFirm\Bundle\OrderBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\OrderBundle\Repository\PromocodeEnumRepository")
 */
class PromocodeEnum implements UuidEntityInterface
{
    use UuidTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=false, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=false)
     */
    private $calculate;

    /**
     * Proxy translation method
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        /** @var Translation|PromocodeEnumTranslation $translation */
        $translation = $this->translate(null, true);

        return $translation->getName();
    }

    /**
     * Proxy Translation Method
     *
     * @param string $name
     * @param string $locale
     *
     * @return PromocodeEnum
     */
    public function setName(string $name, string $locale): PromocodeEnum
    {
        /** @var Translation|PromocodeEnumTranslation $translation */
        $translation = $this->translate($locale, true);
        $translation->setName($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param null|string $code
     *
     * @return PromocodeEnum
     */
    public function setCode(?string $code): PromocodeEnum
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCalculate(): string
    {
        return (string) $this->calculate;
    }

    /**
     * @param null|string $calculate
     *
     * @return PromocodeEnum
     */
    public function setCalculate(?string $calculate): PromocodeEnum
    {
        $this->calculate = $calculate;

        return $this;
    }
}
