<?php

namespace App\Entity\Admin;

use App\Repository\Admin\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'client:item']),
        new GetCollection(normalizationContext: ['groups' => 'client:list'])
    ],
    order: ['id'=> 'DESC'],
    paginationEnabled: false,
)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:list', 'client:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $address = null;

    #[ORM\Column(length: 50)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $city = null;

    #[ORM\Column(length: 5)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 14)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $phone = null;

    #[ORM\Column(length: 50)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $email = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'client')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $members = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeClient $typeclient = null;

    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'client')]
    private Collection $services;

    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'clientServiceChoise')]
    private Collection $servicesClientChoice;

    #[ORM\OneToMany(mappedBy: 'Client', targetEntity: FicheService::class)]
    #[Groups(['client:item'])]
    private Collection $ficheServices;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['client:item'])]
    private ?string $siren = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(nullable: true)]
    private ?int $tva = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Activity_pro = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $director = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(nullable: true)]
    private ?int $logoSize = null;

    #[ORM\Column(nullable: true)]
    private ?bool $IsSupprLogo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isFavori = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nameStructure = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $surnameStructure = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->services = new ArrayCollection();
        $this->servicesClientChoice = new ArrayCollection();
        $this->ficheServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getMembers(): ?member
    {
        return $this->members;
    }

    public function setMembers(?member $members): self
    {
        $this->members = $members;

        return $this;
    }

    public function getTypeclient(): ?TypeClient
    {
        return $this->typeclient;
    }

    public function setTypeclient(?TypeClient $typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addClient($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            $service->removeClient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServicesClientChoice(): Collection
    {
        return $this->servicesClientChoice;
    }

    public function addServicesClientChoice(Service $servicesClientChoice): self
    {
        if (!$this->servicesClientChoice->contains($servicesClientChoice)) {
            $this->servicesClientChoice->add($servicesClientChoice);
            $servicesClientChoice->addClientServiceChoise($this);
        }

        return $this;
    }

    public function removeServicesClientChoice(Service $servicesClientChoice): self
    {
        if ($this->servicesClientChoice->removeElement($servicesClientChoice)) {
            $servicesClientChoice->removeClientServiceChoise($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheService>
     */
    public function getFicheServices(): Collection
    {
        return $this->ficheServices;
    }

    public function addFicheService(FicheService $ficheService): self
    {
        if (!$this->ficheServices->contains($ficheService)) {
            $this->ficheServices->add($ficheService);
            $ficheService->setClient($this);
        }

        return $this;
    }

    public function removeFicheService(FicheService $ficheService): self
    {
        if ($this->ficheServices->removeElement($ficheService)) {
            // set the owning side to null (unless already changed)
            if ($ficheService->getClient() === $this) {
                $ficheService->setClient(null);
            }
        }

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(?int $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getActivityPro(): ?string
    {
        return $this->Activity_pro;
    }

    public function setActivityPro(?string $Activity_pro): self
    {
        $this->Activity_pro = $Activity_pro;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): self
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }

    public function setLogoSize(?int $logoSize): self
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    public function isIsSupprLogo(): ?bool
    {
        return $this->IsSupprLogo;
    }

    public function setIsSupprLogo(?bool $IsSupprLogo): self
    {
        $this->IsSupprLogo = $IsSupprLogo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isIsFavori(): ?bool
    {
        return $this->isFavori;
    }

    public function setIsFavori(bool $isFavori): self
    {
        $this->isFavori = $isFavori;

        return $this;
    }

    public function getNameStructure(): ?string
    {
        return $this->nameStructure;
    }

    public function setNameStructure(?string $nameStructure): self
    {
        $this->nameStructure = $nameStructure;

        return $this;
    }

    public function getSurnameStructure(): ?string
    {
        return $this->surnameStructure;
    }

    public function setSurnameStructure(?string $surnameStructure): self
    {
        $this->surnameStructure = $surnameStructure;

        return $this;
    }
}
