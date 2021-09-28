<?php

declare(strict_types=1);

namespace App\Entity\Factory;

use App\Entity\Flow;
use DateTimeImmutable;
use Symfony\Component\DomCrawler\Crawler;

class FlowFactory
{
    public static function fromNode(int $riverId, int $stationId, Crawler $node): Flow
    {
        preg_match('/background-color:\s*(#\d+);/i', $node->filterXPath('//td[3]')->attr('style') ?? '', $status);

        return new Flow(
            riverId: $riverId,
            stationId: $stationId,
            datetime: new DateTimeImmutable($node->filterXPath('//td[1]')->text()),
            level: (float) $node->filterXPath('//td[2]')
                ->text(),
            flow: (float) str_replace(',', '.', $node->filterXPath('//td[3]')->text()),
            createdAt: new DateTimeImmutable(),
            status: $status[1] ?? null
        );
    }
}
