<?php

namespace App\Entity\Gestapp;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Gestapp\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column]
    private ?\DateTimeInterface $invoiceAt = null;

    #[ORM\ManyToOne]
    private ?Client $refCustomer = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getInvoiceAt(): ?\DateTimeInterface
    {
        return $this->invoiceAt;
    }

    public function setInvoiceAt(\DateTimeInterface $invoiceAt): static
    {
        $this->invoiceAt = $invoiceAt;

        return $this;
    }

    public function getRefCustomer(): ?Client
    {
        return $this->refCustomer;
    }

    public function setRefCustomer(?Client $refCustomer): static
    {
        $this->refCustomer = $refCustomer;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
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
}
