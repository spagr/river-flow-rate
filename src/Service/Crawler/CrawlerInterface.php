<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use App\Entity\Flow;
use App\Service\Crawler\Exception\CrawlerClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface CrawlerInterface
{
    public function __construct(HttpClientInterface $client);

    /**
     * @throws CrawlerClientException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * @return array<Flow>
     */
    public function getFlows(int $stationId): array;
}
