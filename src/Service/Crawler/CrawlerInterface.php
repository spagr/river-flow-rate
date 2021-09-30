<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use App\Entity\Flow;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface CrawlerInterface
{
    public function __construct(HttpClientInterface $client);

    /**
     * @return array<Flow>
     */
    public function getFlows(int $stationId): array;
}
