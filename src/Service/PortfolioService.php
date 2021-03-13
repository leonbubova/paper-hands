<?php


namespace App\Service;


use App\Entity\Portfolio;
use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class PortfolioService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addBalance(Portfolio $portfolio, int $amount): Portfolio
    {
        $portfolio->setBalance($portfolio->getBalance() + $amount);

        $this->em->persist($portfolio);
        $this->em->flush();

        return $portfolio;
    }

    public function removeBalance(Portfolio $portfolio, int $amount): Portfolio
    {
        if($amount > $portfolio->getBalance())
        {
            throw new \Exception("Not enough balance for transaction. Balance: " . $portfolio->getBalance());
        }

        $portfolio->setBalance($portfolio->getBalance() - $amount);

        $this->em->persist($portfolio);
        $this->em->flush();

        return $portfolio;
    }
}
