<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class EmployeeGroupTranslation
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Entity
 *
 * @ORM\Entity
 */
class EmployeeGroupTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(length=155)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return EmployeeGroupTranslation
     */
    public function setName(?string $name): EmployeeGroupTranslation
    {
        $this->name = $name;

        return $this;
    }
}
