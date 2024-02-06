<?php

namespace App\Entity\Gestapp;

use App\Repository\Gestapp\InvoiceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $quantityHour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantityHour(): ?\DateTimeInterface
    {
        return $this->quantityHour;
    }

    public function setQuantityHour(\DateTimeInterface $quantityHour): static
    {
        $this->quantityHour = $quantityHour;

        return $this;
    }
}
