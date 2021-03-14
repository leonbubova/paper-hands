<?php

namespace App\Controller;

use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
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
     * @Route("/portfolio", name="portfolio")
     */
    public function index(): Response
    {
        $portfolio = $this->entityManager->find(Portfolio::class, 1);

        return $this->render('portfolio/index.html.twig', [
            'controller_name' => 'PortfolioController',
            'portfolio' => $portfolio
        ]);
    }
}
