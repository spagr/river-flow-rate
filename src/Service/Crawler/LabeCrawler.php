<?php

declare(strict_types=1);

namespace App\Service\Crawler;

use App\Entity\Factory\FlowFactory;
use App\Entity\Flow;
use App\Service\Crawler\Exception\CrawlerClientException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LabeCrawler implements CrawlerInterface
{
    public const RIVER_ID = 1;
    public const LANOV_STATION_ID = 228;

    private const RIVER_URL = 'http://www.pla.cz/portal/sap/cz/PC/Mereni.aspx?id=%s&oid=1';
    private const XPATH_FLOW_TABLE_ROWS = '//*[@id="ObsahCPH_DataMereniGV"]/tr[position()>1]';

    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    /**
     * @return array<Flow>
     */
    public function getFlows(int $stationId): array
    {
        $response = $this->client->request('GET', sprintf(self::RIVER_URL, $stationId));

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new CrawlerClientException('Returned HTTP code is not 200.');
        }

        $crawler = new Crawler($response->getContent());
        $items = $crawler->filterXPath(self::XPATH_FLOW_TABLE_ROWS);

        return array_reverse($items->each(function (Crawler $node) use ($stationId): Flow {
            return FlowFactory::fromNode(self::RIVER_ID, $stationId, $node);
        }));
    }
}
