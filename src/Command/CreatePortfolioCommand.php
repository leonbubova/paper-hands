<?php

namespace App\Command;

use App\Entity\Portfolio;
use App\Service\ConversionService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreatePortfolioCommand extends Command
{
    protected static $defaultName = 'create:portfolio';

    protected static $defaultDescription = 'Add a short description for your command';

    private EntityManagerInterface $em;

    /**
     * @var ConversionService
     */
    private ConversionService $conversionService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        ConversionService $conversionService,
        string $name = null
    )
    {
        $this->em = $entityManager;
        $this->conversionService = $conversionService;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('balance', InputArgument::REQUIRED, 'The balance you want to open the portfolio with multiplied by ' . $this->conversionService::CONVERSION_FACTOR)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $balance = $input->getArgument('balance');

        $portfolio = new Portfolio($balance);

        $this->em->persist($portfolio);
        $this->em->flush();

        $io->success('Portfolio created! ID is: ' . $portfolio->getId());

        return Command::SUCCESS;
    }
}
