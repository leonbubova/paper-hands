<?php

namespace App\Command;

use App\Entity\Portfolio;
use App\Service\ApiPriceService;
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

    /**
     * @var ApiPriceService
     */
    private ApiPriceService $apiPriceService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        PositionService $positionService,
        ConversionService $conversionService,
        ApiPriceService $apiPriceService,
        string $name = null
    )
    {
        $this->em = $entityManager;
        $this->positionService = $positionService;
        $this->conversionService = $conversionService;
        $this->apiPriceService = $apiPriceService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('portfolioId', InputArgument::REQUIRED, 'Specify the Portfolio ID for your position')
            ->addArgument('ticker', InputArgument::REQUIRED, 'The Ticker Symbol you want to sell')
            ->addArgument('amount', InputArgument::REQUIRED, 'The amount you want to sell')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $portfolioId = $input->getArgument('portfolioId');
        $ticker = $input->getArgument('ticker');
        $amount = $input->getArgument('amount');

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

        $position = $this->positionService->closePosition($portfolio, $ticker, $amount, $this->apiPriceService->getPrice($ticker));

        $io->success([
            'Position CLOSED for ' . $amount . 'x ' . $ticker . ' at ' . $this->conversionService->convertToCurrency($this->apiPriceService->getPrice($ticker)) . '$ ',
            'for a total of ' . $this->conversionService->convertToCurrency($amount * $this->apiPriceService->getPrice($ticker)) . '$',
            'New Portfolio Balance: ' . $this->conversionService->convertToCurrency($portfolio->getBalance()) . '$'
        ]);

        return Command::SUCCESS;
    }
}
