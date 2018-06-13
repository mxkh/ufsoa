<?php

declare(strict_types=1);

namespace UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class SortableTrait
 *
 * @package UmberFirm\Component\Doctrine\Entity\Gedmo\Sortable
 */
trait SortableTrait
{
    /**
     * @var int
     *
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return (int) $this->position;
    }

    /**
     * @param null|int $position
     *
     * @return $this
     */
    public function setPosition(?int $position)
    {
        $this->position = $position;

        return $this;
    }
}
