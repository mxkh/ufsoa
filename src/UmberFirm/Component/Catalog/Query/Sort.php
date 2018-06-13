<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Query;

/**
 * Class Sort
 *
 * @package UmberFirm\Component\Catalog\Query
 */
final class Sort implements SortInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $order;

    /**
     * @var string
     */
    private $nestedPath;

    /**
     * Sort constructor.
     *
     * @param string $field
     * @param string $order
     * @param string $nestedPath
     */
    public function __construct(string $field, string $order, string $nestedPath = '')
    {
        $this
            ->setField($field)
            ->setOrder($order)
            ->setNestedPath($nestedPath);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return (string) $this->field;
    }

    /**
     * @param string $field
     *
     * @return SortInterface
     */
    public function setField(string $field): SortInterface
    {
        $this->field = $field;

        if (true === $this->isNested()) {
            $this->field = sprintf('%s.%s', $this->getNestedPath(), $field);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return (string) $this->order;
    }

    /**
     * @param string $order
     *
     * @return SortInterface
     */
    public function setOrder(string $order): SortInterface
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getNestedPath(): string
    {
        return (string) $this->nestedPath;
    }

    /**
     * @param string $path
     *
     * @return SortInterface
     */
    public function setNestedPath(string $path): SortInterface
    {
        $this->nestedPath = $path;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNested(): bool
    {
        return (bool) (false === empty($this->getNestedPath()));
    }
}
