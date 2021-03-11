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

class OpenPositionCommand extends Command
{
    protected static $defaultName = 'open:position';
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
            ->addArgument('ticker', InputArgument::REQUIRED, 'The Ticker Symbol you want to buy')
            ->addArgument('amount', InputArgument::REQUIRED, 'The amount you want to buy')
            ->addArgument('price', InputArgument::REQUIRED, 'The price you are buying at')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $portfolioId = $input->getArgument('portfolioId');
        $ticker = $input->getArgument('ticker');
        $amount = $input->getArgument('amount');
        $price = $input->getArgument('price');

        $portfolio = $this->em->find(Portfolio::class, $portfolioId);

        $position = $this->positionService->createPosition($portfolio, $ticker, $amount, $price);

        $this->em->persist($position);
        $this->em->flush();

        $io->success('Position opened for ' . $position->getAmount() . 'x ' . $position->getTicker() . ' at ' . $position->getOpeningPrice() / 10000 . '$.');

        return Command::SUCCESS;
    }
}
