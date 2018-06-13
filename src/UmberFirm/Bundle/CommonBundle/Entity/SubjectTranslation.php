<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class SubjectTranslation
 *
 * @package UmberFirm\Bundle\CommonBundle\Entity
 *
 * @ORM\Entity
 */
class SubjectTranslation
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
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $name;

    /**
     * @param string $name
     *
     * @return SubjectTranslation
     */
    public function setName(?string $name): SubjectTranslation
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }
}
