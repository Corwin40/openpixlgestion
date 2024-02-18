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

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    private ?Invoice $refInvoice = null;

    #[ORM\Column]
    private ?\DateInterval $hour = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $montantHt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $montantTtc = null;

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

    public function getRefInvoice(): ?Invoice
    {
        return $this->refInvoice;
    }

    public function setRefInvoice(?Invoice $refInvoice): static
    {
        $this->refInvoice = $refInvoice;

        return $this;
    }

    public function getHour(): ?\DateInterval
    {
        return $this->hour;
    }

    public function setHour(?\DateInterval $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getMontantHt(): ?string
    {
        return $this->montantHt;
    }

    public function setMontantHt(string $montantHt): static
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getMontantTtc(): ?string
    {
        return $this->montantTtc;
    }

    public function setMontantTtc(string $montantTtc): static
    {
        $this->montantTtc = $montantTtc;

        return $this;
    }
}
