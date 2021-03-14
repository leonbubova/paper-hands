<?php

namespace App\Command;

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

    public function __construct(TestService $testService, string $name = null)
    {
        $this->testService = $testService;
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

        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://financialmodelingprep.com/']);
        $response = $client->request('GET', 'api/v3/profile/AAPL?apikey=demo');

        $json = json_decode($response->getBody());


        $io->writeln($this->testService->test());

        return Command::SUCCESS;
    }
}
