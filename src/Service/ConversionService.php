<?php


namespace App\Service;


use App\Entity\Portfolio;
use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;

class ConversionService
{
    public const CONVERSION_FACTOR = 10000;

    public function convertCurrency(int $amount): float
    {
        return $amount / self::CONVERSION_FACTOR;
    }
}