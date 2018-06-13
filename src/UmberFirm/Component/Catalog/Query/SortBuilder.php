<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

use Doctrine\Common\Collections\Collection;

/**
 * Class SortBuilder
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class SortBuilder implements SortBuilderInterface
{
    /**
     * @param Collection $sortCollection
     *
     * @return \Generator
     */
    public function build(Collection $sortCollection): \Generator
    {
        foreach ($sortCollection as $field => $order) {
            //TODO Add Sort factory with prototype pattern
            yield new Sort($field, $order);
        }
    }
}
