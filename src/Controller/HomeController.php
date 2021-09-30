<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Flow;
use App\Repository\FlowRepository;
use App\Service\Crawler\LabeCrawler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        ChartBuilderInterface $chartBuilder,
        FlowRepository $flowRepository
    ): Response
    {
        $limit = (int) $request->get('limit', 200);
        $flows = $flowRepository->findLastStationFlows(LabeCrawler::LANOV_STATION_ID, $limit);
        $flows = array_reverse($flows);
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            //'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'labels' => array_map(
                static fn (Flow $flow): string => $flow->getDatetime()
                    ->format('Y-m-d H:i:s'),
                $flows
            ),
            'datasets' => [
                [
                    'label' => 'Flow',
                    'backgroundColor' => '#6495ED',
                    'borderColor' => '#FF7F50',
                    'data' => array_map(static fn (Flow $flow): array => [
                        'x' => $flow->getDatetime()
                            ->format('Y-m-d H:i:s'),
                        'y' => $flow->getFlow(),
                    ], $flows),
                ],
            ],
        ]);

        $chart->setOptions([/* ... */]);

        return $this->render('home/index.html.twig', [
            'limit' => $limit,
            'chart' => $chart,
        ]);
    }
}
