<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\FlowRepository;
use App\Service\Crawler\CrawlerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:crawler',
    description: 'Crawl latest river flows',
)]
class CrawlerCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CrawlerFactory $crawlerFactory,
        private FlowRepository $flowRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('riverId', InputArgument::REQUIRED, 'River Id')
            ->addArgument('stationId', InputArgument::REQUIRED, 'Station Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $riverId = (int) $input->getArgument('riverId');
        $stationId = (int) $input->getArgument('stationId');
        $crawler = $this->crawlerFactory->createRiverCrawler($riverId);
        $flows = $crawler->getFlows($stationId);
        $lastFlow = $this->flowRepository->findOneBy([
            'riverId' => $riverId,
            'stationId' => $stationId,
        ], [
            'datetime' => 'DESC',
        ]);

        foreach ($flows as $flow) {
            $output->write(
                sprintf('Flow from %s is %s m3/s, saving: ', $flow->getDatetime()->format(DATE_ATOM), $flow->getFlow())
            );

            if ($lastFlow === null || $flow->getDatetime() > $lastFlow->getDatetime()) {
                $this->entityManager->persist($flow);
                $output->writeln('<info>OK</info>');
            } else {
                $output->writeln('<error>already exist</error>');
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
