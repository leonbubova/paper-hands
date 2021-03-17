<?php

namespace App\Controller\Api;

use App\Entity\Portfolio;
use App\Service\ConversionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * Class ApiPortfolioController
 * @package App\Controller\Api
 * @Route ("/api")
 */
class ApiPortfolioController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ConversionService
     */
    private ConversionService $conversionService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        ConversionService $conversionService
    )
    {
        $this->entityManager = $entityManager;
        $this->conversionService = $conversionService;
    }

    /**
     * @Route("/portfolio", name="api_portfolio")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Portfolio $portfolio */
        $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        return new JsonResponse([
            'id' => $portfolio->getId(),
            'username' => $portfolio->getUser()->getUsername(),
            'balance' => $this->conversionService->convertToCurrency($portfolio->getBalance())
        ]);
    }
}
