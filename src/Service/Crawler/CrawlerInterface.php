<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface CrawlerInterface
{
    public function __construct(HttpClientInterface $client);

    public function getFlows(int $stationId);
}
