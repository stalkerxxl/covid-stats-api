<?php

namespace App\Service;

use App\Exception\ApiException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $client;
    private string $method = 'GET';
    private string $endpoint;
    private array $params = [];


    public function __construct(HttpClientInterface $covid19apiClient)
    {
        $this->client = $covid19apiClient;
    }

    /**
     * @return array
     * @throws ApiException
     */
    private function _send(): array
    {
        try {
            $response = $this->client->request($this->method, $this->endpoint,
                ['query' => $this->params]);

            return $response->toArray();

        } catch (ExceptionInterface $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ApiException
     */
    public function getCountriesList(): array
    {
        $this->endpoint = '/countries';

        return $this->_send();
    }

    /**
     * @throws ApiException
     */
    public function getSummaryStat(): array
    {
        $this->endpoint = '/summary';

        return $this->_send();
    }
}