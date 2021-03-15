<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Service\PositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PositionController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    private PositionService $positionService;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        PositionService $positionService
    )
    {
        $this->entityManager = $entityManager;
        $this->positionService = $positionService;
    }

    /**
     * @Route("/position/open", name="position_open")
     * @param Request $request
     * @return Response
     */
    public function open(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Portfolio $portfolio */
        $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        if($request->request->get('ticker') !== null)
        {
            $this->positionService->openPosition($portfolio, $request->request->get('ticker'), $request->request->get('amount'));

            return $this->redirectToRoute('portfolio');
        }

        return $this->render('position/open.html.twig', [
            'controller_name' => 'PositionController'
        ]);
    }

    /**
     * @Route("/position/close", name="position_close")
     * @param Request $request
     * @return Response
     */
    public function close(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Portfolio $portfolio */
        $portfolio = $this->entityManager->getRepository(Portfolio::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        if($request->request->get('ticker') !== null)
        {
            $this->positionService->closePosition($portfolio, $request->request->get('ticker'), $request->request->get('amount'));

            return $this->redirectToRoute('portfolio');
        }

        return $this->render('position/close.html.twig', [
            'controller_name' => 'PositionController'
        ]);
    }
}
