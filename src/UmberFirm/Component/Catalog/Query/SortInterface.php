<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

/**
 * Class SortInterface
 *
 * @package UmberFirm\Component\Catalog\Query
 */
interface SortInterface
{
    /**
     * Predefined order types
     */
    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';

    /**
     * Predefined nested path
     */
    const NESTED_PATH = 'search_data';

    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @param string $field
     *
     * @return SortInterface
     */
    public function setField(string $field): SortInterface;

    /**
     * @return string
     */
    public function getOrder(): string;

    /**
     * @param string $order
     *
     * @return SortInterface
     */
    public function setOrder(string $order): SortInterface;

    /**
     * @return string
     */
    public function getNestedPath(): string;

    /**
     * @param string $path
     *
     * @return SortInterface
     */
    public function setNestedPath(string $path): SortInterface;

    /**
     * @return bool
     */
    public function isNested(): bool;
}
