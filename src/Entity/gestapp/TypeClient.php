<?php

namespace App\Entity\Gestapp;

use App\Repository\Gestapp\TypeClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeClientRepository::class)]
class TypeClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isFormCompleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function isIsFormCompleted(): ?bool
    {
        return $this->isFormCompleted;
    }

    public function setIsFormCompleted(bool $isFormCompleted): self
    {
        $this->isFormCompleted = $isFormCompleted;

        return $this;
    }
}
