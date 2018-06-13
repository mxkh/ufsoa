<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Adapter;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;

/**
 * Class WayForPayPaymentService
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Adapter
 */
final class WayForPayAdapter implements PaymentInterface
{
    const NAME = 'WayForPay';
    const AUTO_MERCHANT_TRANSACTION_SECURE_TYPE = 'AUTO';
    const TEST_MERCHANT = 'test_merch_n1';
    const MERCHANT_SECURE_KEY = 'flk3409refn54t54t*FNJRET';
    const SERVICE_URL = 'umberfirm__payment__post_payment_purchase';

    //WayForPay KEYS NAME
    const KEY_MERCHANT_AUTH_TYPE = 'merchantAuthType';
    const KEY_MERCHANT_TRANSACTION_TYPE = 'merchantTransactionType';
    const KEY_MERCHANT_TRANSACTION_SECURE_TYPE = 'merchantTransactionSecureType';
    const KEY_MERCHANT_DOMAIN_NAME = 'merchantDomainName';
    const KEY_RETURN_URL = 'returnUrl';
    const KEY_SERVICE_URL = 'serviceUrl';

    /**
     * @var \WayForPay
     */
    private $wayForPay;

    /**
     * @var ShopPaymentSettings
     */
    private $paymentSettings;

    /**
     * @var string
     */
    private $serviceUrl;

    /**
     * WayForPayAdapter constructor.
     *
     * @param \WayForPay $wayForPay
     * @param ShopPaymentSettings $paymentSettings
     * @param RouterInterface $router
     */
    public function __construct(\WayForPay $wayForPay, ShopPaymentSettings $paymentSettings, RouterInterface $router)
    {
        $this->wayForPay = $wayForPay;
        $this->paymentSettings = $paymentSettings;
        $this->serviceUrl = $router->generate(WayForPayAdapter::SERVICE_URL, [], RouterInterface::ABSOLUTE_URL);
    }

    /**
     * @param array $data must have next keys: orderReference, orderDate, amount, currency, productName, productCount, productPrice
     *
     * @throws  \InvalidArgumentException
     *
     * {@inheritdoc}
     */
    public function generatePaymentUrl(array $data, array $options = []): string
    {
        $data = array_merge(
            $data,
            [
                self::KEY_MERCHANT_AUTH_TYPE => $this->paymentSettings->getMerchantAuthType(),
                self::KEY_MERCHANT_TRANSACTION_TYPE => $this->paymentSettings->getMerchantTransactionType(),
                self::KEY_MERCHANT_TRANSACTION_SECURE_TYPE => self::AUTO_MERCHANT_TRANSACTION_SECURE_TYPE,
                self::KEY_MERCHANT_DOMAIN_NAME => $this->paymentSettings->getDomainName(),
                self::KEY_RETURN_URL => $this->paymentSettings->getReturnUrl(),
                self::KEY_SERVICE_URL => $this->serviceUrl,
            ]
        );

        return $this->wayForPay->generatePurchaseUrl($data);
    }

    /**
     * @param array $data must have next keys: orderReference, amount, currency
     *
     * @throws  \InvalidArgumentException
     *
     * {@inheritdoc}
     */
    public function holdCompletion(array $data, array $options = []): JsonResponse
    {
        return new JsonResponse($this->wayForPay->settle($data));
    }

    /**
     * @param array $data must have next key: orderReference
     *
     * @throws  \InvalidArgumentException
     *
     * {@inheritdoc}
     */
    public function checkPaymentStatus(array $data, array $options = []): JsonResponse
    {
        return new JsonResponse($this->wayForPay->checkStatus($data));
    }

    /**
     * @param array $data must have next key: orderReference, amount, currency, comment
     *
     * @throws  \InvalidArgumentException
     *
     * {@inheritdoc}
     */
    public function refundPayment(array $data, array $options = []): JsonResponse
    {
        return new JsonResponse($this->wayForPay->refund($data));
    }
}
