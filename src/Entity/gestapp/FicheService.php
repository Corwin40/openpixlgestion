<?php

namespace App\Entity\Gestapp;

use App\Entity\Admin\Member;
use App\Repository\Gestapp\FicheServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FicheServiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class FicheService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['client:item'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['client:item'])]
    private ?string $descriptif = null;

    #[ORM\ManyToOne]
    #[Groups(['client:item'])]
    private ?Service $service = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['client:item'])]
    private ?string $statut = null;

    #[ORM\ManyToOne]
    #[Groups(['client:item'])]
    private ?Member $author = null;

    #[ORM\ManyToOne(inversedBy: 'ficheServices')]
    private ?Client $Client = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['client:item'])]
    private ?\DateInterval $engagement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['client:item'])]
    private ?\DateTimeInterface $echeance = null;


    #[ORM\OneToMany(mappedBy: 'ficheservice', targetEntity: Intervention::class)]
    #[Groups(['client:item'])]
    private Collection $interventions;

    #[ORM\Column(nullable: true)]
    #[Groups(['client:item'])]
    private ?int $package = null;
    #[ORM\Column]
    #[Groups(['client:item'])]
    private ?int $choicePrice = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['client:item'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['client:item'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?int $priceHour = null;

    #[ORM\Column]
    private ?int $priceBundle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $tva = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    public function getAuthor(): ?Member
    {
        return $this->author;
    }

    public function setAuthor(?Member $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }


    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setFicheservice($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getFicheservice() === $this) {
                $intervention->setFicheservice(null);
            }
        }

        return $this;
    }

    public function getPackage(): ?int
    {
        return $this->package;
    }

    public function setPackage(?int $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getEngagement(): ?\DateInterval
    {
        return $this->engagement;
    }

    public function setEngagement(?\DateInterval $engagement): self
    {
        $this->engagement = $engagement;

        return $this;
    }

    public function getChoicePrice(): ?int
    {
        return $this->choicePrice;
    }

    public function setChoicePrice(int $choicePrice): self
    {
        $this->choicePrice = $choicePrice;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getPriceHour(): ?int
    {
        return $this->priceHour;
    }

    public function setPriceHour(int $priceHour): static
    {
        $this->priceHour = $priceHour;

        return $this;
    }

    public function getPriceBundle(): ?int
    {
        return $this->priceBundle;
    }

    public function setPriceBundle(int $priceBundle): static
    {
        $this->priceBundle = $priceBundle;

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
