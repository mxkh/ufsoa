<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Filter;

use UmberFirm\Component\Catalog\Query\QueryInterface;

/**
 * Class AbstractNestedQuery
 *
 * @package UmberFirm\Component\Catalog\Filter
 */
abstract class AbstractNestedQuery implements NestedQueryInterface
{
    /**
     * @var QueryInterface
     */
    protected $query;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $value;

    /**
     * @var string
     */
    protected $nestedFieldFormat = '%s.%s';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): NestedQueryInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): array
    {
        return (array) $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(array $value): NestedQueryInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(string $path): NestedQueryInterface
    {
        // TODO: Implement setPath() method.

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyFieldName(): string
    {
        return (string) sprintf($this->nestedFieldFormat, $this->getPath(), self::KEY_FIELD_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setKeyFieldName(string $name): NestedQueryInterface
    {
        // TODO: Implement setKeyFieldName() method.

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueFieldName(): string
    {
        return (string) sprintf($this->nestedFieldFormat, $this->getPath(), self::VALUE_FIELD_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setValueFieldName(string $name): NestedQueryInterface
    {
        // TODO: Implement setValueFieldName() method.

        return $this;
    }
}
