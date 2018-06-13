<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Services\Format;

/**
 * Class FormatFactory
 *
 * @package UmberFirm\Bundle\SupplierBundle\Services\Parser\Format
 */
class FormatFactory
{
    /**
     * @var array
     */
    private static $validators = [];

    /**
     * ProductValidatorFactory constructor.
     */
    public function __construct()
    {
        self::$validators = self::versions();
    }

    /**
     * @param mixed $data
     * @param string $version
     *
     * @throws \Exception
     *
     * @return FormatInterface
     */
    public function create($data, string $version): FormatInterface
    {
        if (false === self::isValidVersion($version)) {
            throw new \Exception('Invalid version '.$version);
        }

        return new self::$validators[$version]($data);
    }

    /**
     * @return array
     */
    public static function versions(): array
    {
        return [
            FormatV1::VERSION => FormatV1::class,
        ];
    }

    /**
     * @param string $version
     *
     * @return bool
     */
    public static function isValidVersion(string $version): bool
    {
        return array_key_exists($version, self::versions());
    }
}
