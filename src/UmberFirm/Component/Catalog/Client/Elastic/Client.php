<?php

declare(strict_types=1);

namespace UmberFirm\Component\Catalog\Client\Elastic;

use Elastica\Exception\ExceptionInterface;
use Elastica\Request;
use Elastica\Response;
use FOS\ElasticaBundle\Elastica\Client as BaseClient;

/**
 * Class Client
 *
 * @package UmberFirm\Component\Catalog\Client\Elastica
 */
class Client extends BaseClient
{
    /**
     * {@inheritdoc}
     */
    public function request($path, $method = Request::GET, $data = [], array $query = []): Response
    {
        try {
            return parent::request($path, $method, $data, $query);
        } catch (ExceptionInterface $e) {
            if (null !== $this->_logger) {
                $this->_logger->warning(
                    'Failed to send a request to ElasticSearch',
                    [
                        'exception' => $e->getMessage(),
                        'path' => $path,
                        'method' => $method,
                        'data' => $data,
                        'query' => $query,
                    ]
                );
            }

            return new Response('{"took":0,"timed_out":false,"hits":{"total":0,"max_score":0,"hits":[]}}');
        }
    }
}
