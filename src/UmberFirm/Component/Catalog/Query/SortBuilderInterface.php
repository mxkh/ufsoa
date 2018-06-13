<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Doctrine\Common\Collections\Collection;

/**
 * Class SortBuilderInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface SortBuilderInterface
{
    /**
     * @param Collection $sortCollection
     *
     * @return \Generator
     */
    public function build(Collection $sortCollection): \Generator;
}
