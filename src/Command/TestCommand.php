<?php

namespace App\Command;

use App\Service\ApiPriceService;
use App\Service\TestService;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'test:command';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var TestService
     */
    private TestService $testService;

    /**
     * @var ApiPriceService
     */
    private ApiPriceService $apiPriceService;

    public function __construct(
        TestService $testService,
        ApiPriceService $apiPriceService,
        string $name = null
    )
    {
        $this->testService = $testService;
        $this->apiPriceService = $apiPriceService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln($this->apiPriceService->getPrice('GOOG'));

        return Command::SUCCESS;
    }
}
