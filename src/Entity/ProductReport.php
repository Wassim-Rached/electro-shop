<?php

namespace App\Entity;

use App\Repository\ProductReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductReportRepository::class)]
class ProductReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ApplicationUser $byUser = null;

    #[ORM\ManyToOne(inversedBy: 'productReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $toProduct = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getByUser(): ?ApplicationUser
    {
        return $this->byUser;
    }

    public function setByUser(?ApplicationUser $byUser): static
    {
        $this->byUser = $byUser;

        return $this;
    }

    public function getToProduct(): ?Product
    {
        return $this->toProduct;
    }

    public function setToProduct(?Product $toProduct): static
    {
        $this->toProduct = $toProduct;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }
}
