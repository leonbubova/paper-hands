<?php

namespace App\Controller\Api;

use App\Entity\Portfolio;
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

    public function __construct
    (
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/portfolio", name="api_portfolio")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        return new JsonResponse(['portfolio' => $portfolio]);
    }
}
