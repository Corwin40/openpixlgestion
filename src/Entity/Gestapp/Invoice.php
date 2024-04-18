<?php

namespace App\Entity\Gestapp;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Gestapp\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptif = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column(type: 'datetime')]
    private $invoiceAt = null;

    #[ORM\ManyToOne]
    private ?Client $refCustomer = null;

    #[ORM\OneToMany(mappedBy: 'refInvoice', targetEntity: InvoiceItem::class)]
    private Collection $invoiceItems;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $total = '0';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $tva = '0';
    
    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setRefInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getRefInvoice() === $this) {
                $invoiceItem->setRefInvoice(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
}
