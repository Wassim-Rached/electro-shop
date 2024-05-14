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
    private ?ApplicationUser $by_user = null;

    #[ORM\ManyToOne(inversedBy: 'productReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $to_product = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getByUser(): ?ApplicationUser
    {
        return $this->by_user;
    }

    public function setByUser(?ApplicationUser $by_user): static
    {
        $this->by_user = $by_user;

        return $this;
    }

    public function getToProduct(): ?Product
    {
        return $this->to_product;
    }

    public function setToProduct(?Product $to_product): static
    {
        $this->to_product = $to_product;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
