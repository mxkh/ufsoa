<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use UmberFirm\Component\Doctrine\Uuid\UuidTrait;

/**
 * Class Shop
 *
 * @package UmberFirm\Bundle\ShopBundle\Entity
 *
 * @ORM\Entity(repositoryClass="UmberFirm\Bundle\ShopBundle\Repository\ShopRepository")
 */
class Shop implements UuidEntityInterface, UserInterface
{
    use TimestampableEntity;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ShopGroup
     *
     * @ORM\ManyToOne(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopGroup", inversedBy="shops")
     * @ORM\JoinColumn(name="shop_group_id", referencedColumnName="id")
     */
    private $shopGroup;

    /**
     * @var Collection|Supplier[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\SupplierBundle\Entity\Supplier", mappedBy="shops")
     * @ORM\JoinTable(name="shop_supplier")
     */
    private $suppliers;

    /**
     * @var Collection|ShopSettings[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopSettings",
     *     mappedBy="shop",
     *     cascade={"remove"}
     * )
     */
    private $shopSettings;

    /**
     * @var Collection|Manufacturer[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer", mappedBy="shops")
     * @ORM\JoinTable(name="manufacturer_shop")
     */
    private $manufacturers;

    /**
     * @var Collection|Store[]
     *
     * @ORM\ManyToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\Store", mappedBy="shops", cascade={"remove"})
     */
    private $stores;

    /**
     * @var Collection|Category[]
     *
     * @ORM\OneToMany(
     *     targetEntity="UmberFirm\Bundle\CategoryBundle\Entity\Category",
     *     mappedBy="shop",
     *     cascade={"remove"}
     * )
     */
    private $categories;

    /**
     * @var ArrayCollection|ShopLanguage[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage", mappedBy="shop")
     */
    private $languages;

    /**
     * @var Collection|ShopCurrency[]
     *
     * @ORM\OneToMany(targetEntity="UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency", mappedBy="shop")
     */
    private $currencies;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $apiKey;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * Shop constructor.
     */
    public function __construct()
    {
        $this->suppliers = new ArrayCollection();
        $this->shopSettings = new ArrayCollection();
        $this->manufacturers = new ArrayCollection();
        $this->stores = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->currencies = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param null|string $name
     *
     * @return Shop
     */
    public function setName(?string $name): Shop
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @return null|ShopGroup
     */
    public function getShopGroup(): ?ShopGroup
    {
        return $this->shopGroup;
    }

    /**
     * @param ShopGroup|null $shopGroup Defaults as null
     *
     * @return Shop
     */
    public function setShopGroup(?ShopGroup $shopGroup): Shop
    {
        $this->shopGroup = $shopGroup;

        if (null !== $this->shopGroup) {
            $shopGroup->addShop($this);
        }

        return $this;
    }

    /**
     * @return Collection|Manufacturer[]
     */
    public function getManufacturers(): Collection
    {
        return $this->manufacturers;
    }

    /**
     * @param Manufacturer $manufacturer
     *
     * @return Shop
     */
    public function addManufacturer(Manufacturer $manufacturer): Shop
    {
        if (false === $this->manufacturers->contains($manufacturer)) {
            $this->manufacturers->add($manufacturer);
            $manufacturer->addShop($this);
        }

        return $this;
    }

    /**
     * @return Collection|Supplier[]
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    /**
     * @param Supplier $supplier
     *
     * @return Shop
     */
    public function addSupplier(Supplier $supplier): Shop
    {
        if (false === $this->suppliers->contains($supplier)) {
            $this->suppliers->add($supplier);
            $supplier->addShop($this);
        }

        return $this;
    }

    /**
     * @return Collection|ShopSettings[]
     */
    public function getShopSettings(): Collection
    {
        return $this->shopSettings;
    }

    /**
     * @param ShopSettings $shopSettings
     *
     * @return Shop
     */
    public function addShopSettings(ShopSettings $shopSettings): Shop
    {
        if (false === $this->shopSettings->contains($shopSettings)) {
            $this->shopSettings->add($shopSettings);
            $shopSettings->setShop($this);
        }

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    /**
     * @param Store $store
     *
     * @return Shop
     */
    public function addStore(Store $store): Shop
    {
        if (false === $this->stores->contains($store)) {
            $this->stores->add($store);
            $store->addShop($this);
        }

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return Shop
     */
    public function addCategories(Category $category): Shop
    {
        if (false === $this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setShop($this);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return Collection|ShopLanguage[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    /**
     * @return Collection|ShopCurrency[]
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return (string) $this->apiKey;
    }

    /**
     * @param null|string $apiKey
     *
     * @return Shop
     */
    public function setApiKey(?string $apiKey): Shop
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_API_SHOP'];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * @return null|Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param null|Customer $customer
     *
     * @return Shop
     */
    public function setCustomer(?Customer $customer): Shop
    {
        $this->customer = $customer;

        return $this;
    }
}
