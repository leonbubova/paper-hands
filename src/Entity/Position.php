<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 */
class Position
{
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
     * @ORM\Column(type="boolean")
     */
    private bool $open = true;

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
     * Divide by 10000 to get real value
     *
     * @ORM\Column(type="integer")
     */
    private int $openingPrice;

    /**
     * Divide by 10000 to get real value
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $closingPrice = null;

    public function __construct(string $ticker, int $amount, int $openingPrice, Portfolio $portfolio)
    {
        $this->ticker = $ticker;
        $this->amount = $amount;
        $this->openingPrice = $openingPrice;
        $this->portfolio = $portfolio;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): self
    {
        $this->ticker = $ticker;

        return $this;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getPortfolio(): Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
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

    public function getOpeningPrice(): ?int
    {
        return $this->openingPrice;
    }

    public function setOpeningPrice(int $openingPrice): self
    {
        $this->openingPrice = $openingPrice;

        return $this;
    }

    public function getClosingPrice(): ?int
    {
        return $this->closingPrice;
    }

    public function setClosingPrice(?int $closingPrice): self
    {
        $this->closingPrice = $closingPrice;

        return $this;
    }
}
