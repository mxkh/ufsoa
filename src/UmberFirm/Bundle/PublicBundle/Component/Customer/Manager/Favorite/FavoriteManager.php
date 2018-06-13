<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite;

use Predis\ClientInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ProductBundle\Entity\Product;

/**
 * Class FavoriteManager
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite
 */
class FavoriteManager implements FavoriteManagerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $index;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var string
     */
    private $env;

    /**
     * FavoriteManager constructor.
     *
     * @param ClientInterface $client
     * @param string $index
     * @param string $env
     */
    public function __construct(ClientInterface $client, string $index, string $env = '')
    {
        $this->client = $client;
        $this->index = $index;
        $this->env = $env;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Product $product): int
    {
        return (int) $this->client->hsetnx(
            $this->getKey(),
            $product->getId()->toString(),
            $product->getId()->toString()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function remove(array $products): int
    {
        return (int) $this->client->hdel($this->getKey(), $products);
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $products): array
    {
        $result = array_unique($this->client->hmget($this->getKey(), $products));

        return array_values(array_filter($result));
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return (array) $this->client->hkeys($this->getKey());
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(Customer $customer): FavoriteManager
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string
     */
    protected function getKey(): string
    {
        return sprintf('%s:%s:%s', $this->env, $this->index, $this->customer->getId()->toString());
    }
}
