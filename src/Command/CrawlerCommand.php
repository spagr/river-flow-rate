<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Crawler\CrawlerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
        private ManagerRegistry $managerRegistry,
    ) {
        parent::__construct(null);
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

        foreach ($flows as $flow) {
            $output->write(sprintf('Flow from %s is %s m3/s, saving: ', $flow->getDatetime()->format(DATE_ATOM), $flow->getFlow()));

            try {
                $this->entityManager->persist($flow);
                $this->entityManager->flush();
                $output->writeln('<info>OK</info>');
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException) {
                $output->writeln('<error>Duplication</error>');
                $this->managerRegistry->resetManager();
            }
        }

        return Command::SUCCESS;
    }
}
