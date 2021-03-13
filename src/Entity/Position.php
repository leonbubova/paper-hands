<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"portfolio_id", "ticker"})})
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 */
class Position
{
    public const CONVERSION_FACTOR = 10000;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $ticker;

    /**
     * @ORM\ManyToOne(targetEntity=Portfolio::class, inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Portfolio $portfolio;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * Divide by {@see self::CONVERSION_FACTOR} to get real display price
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private int $averagePrice;

    public function __construct(string $ticker, int $amount, int $openingPrice, Portfolio $portfolio)
    {
        $this->ticker = $ticker;
        $this->amount = $amount;
        $this->portfolio = $portfolio;
        $this->averagePrice = $openingPrice;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function getPortfolio(): Portfolio
    {
        return $this->portfolio;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getAveragePrice(): int
    {
        return $this->averagePrice;
    }

    /**
     * @param int $averagePrice
     */
    public function setAveragePrice(int $averagePrice): void
    {
        $this->averagePrice = $averagePrice;
    }
}
