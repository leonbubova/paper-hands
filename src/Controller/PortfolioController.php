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
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        return $this->render('portfolio/index.html.twig', [
            'controller_name' => 'PortfolioController',
            'portfolio' => $portfolio
        ]);
    }
}
