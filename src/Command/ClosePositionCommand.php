<?php

namespace App\Command;

use App\Entity\Portfolio;
use App\Service\ConversionService;
use App\Service\PositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClosePositionCommand extends Command
{
    protected static $defaultName = 'close:position';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var PositionService
     */
    private PositionService $positionService;

    /**
     * @var ConversionService
     */
    private ConversionService $conversionService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        PositionService $positionService,
        ConversionService $conversionService,
        string $name = null
    )
    {
        $this->em = $entityManager;
        $this->positionService = $positionService;
        $this->conversionService = $conversionService;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('portfolioId', InputArgument::REQUIRED, 'Specify the Portfolio ID for your position')
            ->addArgument('ticker', InputArgument::REQUIRED, 'The Ticker Symbol you want to sell')
            ->addArgument('amount', InputArgument::REQUIRED, 'The amount you want to sell')
            ->addArgument('price', InputArgument::REQUIRED, 'The price you are selling at')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $portfolioId = $input->getArgument('portfolioId');
        $ticker = $input->getArgument('ticker');
        $amount = $input->getArgument('amount');
        $price = $input->getArgument('price');

        if($amount < 1)
        {
            throw new \Exception("Amount must be greater than 0");
        }

        /** @var Portfolio $portfolio */
        $portfolio = $this->em->find(Portfolio::class, $portfolioId);

        if($portfolio === null)
        {
            throw new \Exception("Portfolio with ID: " . $portfolioId . " does not exist.");
        }

        $position = $this->positionService->closePosition($portfolio, $ticker, $amount, $price);

        $io->success([
            'Position CLOSED for ' . $amount . 'x ' . $ticker . ' at ' . $this->conversionService->convertCurrency($price) . '$ ',
            'for a total of ' . $this->conversionService->convertCurrency($amount * $price) . '$',
            'New Portfolio Balance: ' . $this->conversionService->convertCurrency($portfolio->getBalance()) . '$'
        ]);

        return Command::SUCCESS;
    }
}
