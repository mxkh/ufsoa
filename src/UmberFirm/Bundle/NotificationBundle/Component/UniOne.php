<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\NotificationBundle\Component;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UniOne
 *
 * @package UmberFirm\Bundle\NotificationBundle\Component
 */
class UniOne
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $username;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $sender;

    /**
     * UniOne constructor.
     * @param string $apiKey
     * @param string $username
     * @param string $apiUrl
     * @param float|null $timeout
     */
    public function __construct(string $apiKey, string $username, string $apiUrl, array $sender, float $timeout = null)
    {
        $this->apiKey = $apiKey;
        $this->username = $username;
        $this->sender = $sender;

        $this->client = new Client([
            'base_uri' => $apiUrl,
            'timeout' => $timeout,
        ]);
    }

    /**
     * @param array $message
     *
     * @return ResponseInterface
     */
    public function sendEmail(array $message): ResponseInterface
    {
        $body = [
            'api_key' => $this->apiKey,
            'username' => $this->username,
            'message' => array_merge($message, $this->sender),
        ];

        return $this->client->post(
            'email/send.json',
            [
                RequestOptions::JSON => $body,
                RequestOptions::HTTP_ERRORS => false,
            ]
        );
    }
}

