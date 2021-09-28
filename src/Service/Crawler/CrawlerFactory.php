<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use App\Service\Crawler\Exception\NotImplementedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlerFactory
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function createRiverCrawler(int $riverId): CrawlerInterface
    {
        $crawler = match ($riverId) {
            1 => LabeCrawler::class,
            default => null,
        };

        if (null === $crawler) {
            throw new NotImplementedException('This river has no crawler implemented yet.');
        }

        return new $crawler($this->client);
    }
}
