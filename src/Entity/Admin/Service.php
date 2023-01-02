<?php

namespace App\Entity\Admin;

use App\Repository\Admin\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $archives = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'service')]
    private ?Member $members = null;

    #[ORM\ManyToMany(targetEntity: Client::class, mappedBy: 'service')]
    private Collection $clients;

    #[ORM\ManyToMany(targetEntity: Statut::class, inversedBy: 'services')]
    private Collection $statut;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->statut = new ArrayCollection();
    }

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

    public function getArchives(): ?string
    {
        return $this->archives;
    }

    public function setArchives(string $archives): self
    {
        $this->archives = $archives;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->addService($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            $client->removeService($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Statut>
     */
    public function getStatut(): Collection
    {
        return $this->statut;
    }

    public function addStatut(Statut $statut): self
    {
        if (!$this->statut->contains($statut)) {
            $this->statut->add($statut);
        }

        return $this;
    }

    public function removeStatut(Statut $statut): self
    {
        $this->statut->removeElement($statut);

        return $this;
    }
}
