<?php


namespace App\Service;


use App\Entity\Portfolio;
use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class PositionService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createPosition(Portfolio $portfolio, string $ticker, int $amount, int $openingPrice): Position
    {
        $position = new Position($ticker, $amount, $openingPrice, $portfolio);

        $this->em->persist($position);
        $this->em->flush();

        return $position;
    }

}