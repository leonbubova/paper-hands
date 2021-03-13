<?php

namespace App\Command;

use App\Entity\Portfolio;
use App\Service\PositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListPositionsCommand extends Command
{
    protected static $defaultName = 'list:positions';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var PositionService
     */
    private PositionService $positionService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        PositionService $positionService,
        string $name = null
    )
    {
        $this->em = $entityManager;
        $this->positionService = $positionService;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('portfolioId', InputArgument::REQUIRED, 'Specify the Portfolio ID for your position')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $portfolioId = $input->getArgument('portfolioId');

        /** @var Portfolio $portfolio */
        $portfolio = $this->em->find(Portfolio::class, $portfolioId);

        if($portfolio === null)
        {
            throw new \Exception("Portfolio with ID: " . $portfolioId . " does not exist.");
        }

        foreach($portfolio->getPositions() as $position)
        {
            $io->write($position->getAmount() . "x ". $position->getTicker() . " Buyin: " . $this->positionService->displayAveragePrice($position));
            $io->newLine();
        }

        return Command::SUCCESS;
    }
}
