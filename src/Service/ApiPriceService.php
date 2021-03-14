<?php


namespace App\Service;


use App\Entity\Portfolio;
use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ApiPriceService
{
    public const FMP_BASE_URI = 'https://financialmodelingprep.com/api/v3/quote/';

    public const FMP_API_KEY_STRING = '?apikey=';

    /**
     * @var ContainerBagInterface
     */
    private ContainerBagInterface $params;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var ConversionService
     */
    private ConversionService $conversionService;

    public function __construct(
        ContainerBagInterface $params,
        ConversionService $conversionService
    )
    {
        $this->client = new Client(['base_uri' => self::FMP_BASE_URI]);
        $this->params = $params;
        $this->conversionService = $conversionService;
    }

    public function getPrice(string $ticker)
    {
        $response = $this->client->request('GET', $ticker . self::FMP_API_KEY_STRING . $this->params->get('fmp.api.key'));
        $json = json_decode($response->getBody());
        $price = $json[0]->price;

        return $this->conversionService->convertToInteger($price);
    }

}