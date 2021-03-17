<?php

namespace App\Controller\Api;

use App\Entity\Portfolio;
use App\Service\PositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiPositionController
 * @package App\Controller\Api
 * @Route ("/api/position")
 */
class ApiPositionController extends AbstractController
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
     * @Route("/open", name="api_position_open")
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

        if($request->request->get('ticker') !== null
            && $request->request->get('amount') !== null)
        {
            $this->positionService->openPosition($portfolio, $request->request->get('ticker'), $request->request->get('amount'));

            return new JsonResponse([
                'message' => 'Position opened.'
            ]);
        }
        else
        {
            return new JsonResponse([
                'message' => 'Could not open position!'
            ]);
        }
    }

    /**
     * @Route("/close", name="api_position_close")
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

        if($request->request->get('ticker') !== null
            && $request->request->get('amount') !== null)
        {
            $this->positionService->closePosition($portfolio, $request->request->get('ticker'), $request->request->get('amount'));

            return new JsonResponse([
                'message' => 'Position closed.'
            ]);
        }
        else
        {
            return new JsonResponse([
                'message' => 'Could not close position!'
            ]);
        }
    }
}
