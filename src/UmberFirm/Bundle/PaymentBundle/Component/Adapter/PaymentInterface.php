<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Component\Adapter;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface PaymentAdapterInterface
 *
 * @package UmberFirm\Bundle\PaymentBundle\Component\Adapter
 */
interface PaymentInterface
{
    /**
     * @param array $data
     * @param array $options
     *
     * @return string
     */
    public function generatePaymentUrl(array $data, array $options = []): string;

    /**
     * @param array $data
     * @param array $options
     *
     * @return JsonResponse
     */
    public function holdCompletion(array $data, array $options = []): JsonResponse;

    /**
     * @param array $data
     * @param array $options
     *
     * @return JsonResponse
     */
    public function checkPaymentStatus(array $data, array $options = []): JsonResponse;

    /**
     * @param array $data
     * @param array $options
     *
     * @return JsonResponse
     */
    public function refundPayment(array $data, array $options = []): JsonResponse;
}
