<?php

namespace App\Entity\Gestapp;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Gestapp\InvoiceRepository;
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
    private ?\DateTimeImmutable $invoiceAt = null;

    #[ORM\Column(length: 255)]
    private ?string $relation = null;

    #[ORM\ManyToOne]
    private ?Client $refCustomer = null;

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

    public function getInvoiceAt(): ?\DateTimeImmutable
    {
        return $this->invoiceAt;
    }

    public function setInvoiceAt(\DateTimeImmutable $invoiceAt): static
    {
        $this->invoiceAt = $invoiceAt;

        return $this;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): static
    {
        $this->relation = $relation;

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
}
