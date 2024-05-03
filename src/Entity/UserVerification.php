<?php

namespace App\Entity;

use App\Repository\UserVerificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserVerificationRepository::class)]
class UserVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $cinPhoto = null;

    #[ORM\Column(length: 255)]
    private ?string $personPhoto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getCinPhoto(): ?string
    {
        return $this->cinPhoto;
    }

    public function setCinPhoto(string $cinPhoto): static
    {
        $this->$cinPhoto = $cinPhoto;

        return $this;
    }

    public function getPersonPhoto(): ?string
    {
        return $this->personPhoto;
    }

    public function setPersonPhoto(string $personPhoto): static
    {
        $this->personPhoto = $personPhoto;

        return $this;
    }
}
