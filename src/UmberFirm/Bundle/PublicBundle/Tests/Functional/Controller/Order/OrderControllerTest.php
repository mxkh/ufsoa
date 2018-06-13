<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\DeliveryBundle\Entity\Delivery;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class OrderControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Order
 */
class OrderControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|Shop[]|Customer[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $sum = new PromocodeEnum();
        $sum->setCode('sum');
        $sum->setName('Sum', 'en');
        $sum->setCalculate('%s - %s');
        $sum->mergeNewTranslations();
        static::getObjectManager()->persist($sum);

        $sum30 = new Promocode();
        /** @var string $locale */
        $sum30->setCode('SALE_30');
        $sum30->setValue(30);
        $sum30->setIsReusable(true);
        $sum30->setPromocodeEnum($sum);
        static::getObjectManager()->persist($sum30);

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $shop = new Shop();
        $shop->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->mergeNewTranslations();
        static::getObjectManager()->persist($customerGroup);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setShop($shop)
            ->setCustomerGroup($customerGroup);
        static::getObjectManager()->persist($customer);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('10 Crosby Derek Lam');
        $manufacturer->setAddress(
            'Derek Lam Online Customer Service 3040 East Ana Street Rancho Dominguez CA 90221',
            'en'
        );
        $manufacturer->addShop($shop);
        $manufacturer->setWebsite('www.dereklam.com');
        $manufacturer->mergeNewTranslations();
        static::getObjectManager()->persist($manufacturer);

        $product10 = new Product();
        $product10->setManufacturer($manufacturer);
        $product10->setName('noski', 'ua');
        $product10->setOutOfStock(true);
        $product10->setShop($shop);
        $product10->mergeNewTranslations();
        static::getObjectManager()->persist($product10);

        $productVariant1 = new ProductVariant();
        $productVariant1->setProduct($product10)
            ->setPrice(1000.00)
            ->setSalePrice(999.99);
        static::getObjectManager()->persist($productVariant1);

        $shoppingCart = new ShoppingCart();
        $shoppingCart->setAmount(123.12);
        $shoppingCart->setShop($shop);
        $shoppingCart->setQuantity(1);
        static::getObjectManager()->persist($shoppingCart);

        $shoppingCartItem = new ShoppingCartItem();
        $shoppingCartItem->setShoppingCart($shoppingCart);
        $shoppingCartItem->setProductVariant($productVariant1);
        $shoppingCartItem->setQuantity(1);
        $shoppingCartItem->setPrice(1000.11);
        $shoppingCartItem->setAmount(123.12);
        static::getObjectManager()->persist($shoppingCartItem);

        $wayForPay = new Payment();
        $wayForPay->setCode('WayForPay');
        $wayForPay->setName('Оплата карткою онлайн Visa/MasterCard (WayForPay)', 'ru');
        $wayForPay->setDescription('При оплаті карткою ми спишемо всі ваші гроші - сарян :)', 'ru');
        $wayForPay->setType(Payment::ONLINE);
        $wayForPay->mergeNewTranslations();
        static::getObjectManager()->persist($wayForPay);

        $wayForPayHM = new ShopPayment();
        $wayForPayHM->setShop($shop);
        $wayForPayHM->setPayment($wayForPay);
        static::getObjectManager()->persist($wayForPayHM);

        $settings = new ShopPaymentSettings();
        $settings->setPublicKey('test_merchant');
        $settings->setPrivateKey('dhkq3vUi94{Z!5frxs(02ML');
        $settings->setReturnUrl('ufsoa.dev/return_url');
        $settings->setMerchantAuthType('SimpleSignature');
        $settings->setDomainName('posh.ua');
        $settings->setMerchantTransactionType('AUTH');
        $settings->setTestMode(true);
        $settings->setShopPayment($wayForPayHM);
        static::getObjectManager()->persist($settings);

        $novaPoshta = new DeliveryGroup();
        $novaPoshta->setCode('nova_poshta');
        $novaPoshta->setName('Nova Poshta', 'en');
        $novaPoshta->setDescription('Nova Poshta description', 'en');
        $novaPoshta->setName('Nova Poshta', 'en');
        $novaPoshta->mergeNewTranslations();
        static::getObjectManager()->persist($novaPoshta);

        $novaPoshtaWarehouse = new Delivery();
        $novaPoshtaWarehouse->setCode('nova_poshta_warehouse');
        $novaPoshtaWarehouse->setName('Nova Poshta Warehouse', 'en');
        $novaPoshtaWarehouse->setDescription('Nova Poshta Warehouse description', 'en');
        $novaPoshtaWarehouse->setGroup($novaPoshta);
        $novaPoshtaWarehouse->mergeNewTranslations();
        static::getObjectManager()->persist($novaPoshtaWarehouse);

        $novaPoshtaWarehouseHM = new ShopDelivery();
        $novaPoshtaWarehouseHM->setShop($shop);
        $novaPoshtaWarehouseHM->setDelivery($novaPoshtaWarehouse);
        static::getObjectManager()->persist($novaPoshtaWarehouseHM);

        $kyiv = new City();
        $kyiv->setName('Kyiv');
        $kyiv->setRef('8d5a980d-391c-11dd-90d9-001a92567626');
        static::getObjectManager()->persist($kyiv);

        $currencyUAH = new Currency();
        $currencyUAH->setCode('UAH');
        $currencyUAH->setName('Гривня');
        static::getObjectManager()->persist($currencyUAH);

        $shopUAH = new ShopCurrency();
        $shopUAH->setIsDefault(false);
        $shopUAH->setCurrency($currencyUAH);
        $shopUAH->setShop($shop);
        static::getObjectManager()->persist($shopUAH);

        static::getObjectManager()->flush();

        self::$entities = [
            'customer' => $customer,
            'shop' => $shop,
            'shoppingCart' => $shoppingCart,
            'promocode' => $sum30,
            'shopPayment' => $wayForPayHM,
            'shopCurrency' => $shopUAH,
            'shopDelivery' => $novaPoshtaWarehouseHM,
            'kyiv' => $kyiv,
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'shoppingCart' => self::$entities['shoppingCart']->getId()->toString(),
            'promocode' => self::$entities['promocode']->getId()->toString(),
            'shopPayment' => self::$entities['shopPayment']->getId()->toString(),
            'shopCurrency' => self::$entities['shopCurrency']->getId()->toString(),
            'shopDelivery' => self::$entities['shopDelivery']->getId()->toString(),
            'firstname' => 'Joe',
            'lastname' => 'Doe',
            'phone' => '+380661231212',
            'city' => self::$entities['kyiv']->getId()->toString(),
            'country' => 'USA',
        ];
    }

    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_order');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionOrderAlreadyExist()
    {
        $uri = $this->router->generate('umberfirm__public__post_order');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostActionWithEmptyData()
    {
        $uri = $this->router->generate('umberfirm__public__post_order');

        $token = [
            'shop' => self::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
