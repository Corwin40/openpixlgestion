<?php

namespace App\Entity\Admin;

use App\Repository\Admin\InterventionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startedAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finishedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $timelaps = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Member $author = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?FicheService $ficheservice = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRecurr = false;

    #[ORM\Column(nullable: true)]
    private ?int $recurrence = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\Column(nullable: true)]
    private ?int $multiple = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeInterface $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getTimelaps(): ?\DateInterval
    {
        return $this->timelaps;
    }

    public function setTimelaps(?\DateInterval $timelaps): self
    {
        $this->timelaps = $timelaps;

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

    public function getFicheservice(): ?FicheService
    {
        return $this->ficheservice;
    }

    public function setFicheservice(?FicheService $ficheservice): self
    {
        $this->ficheservice = $ficheservice;

        return $this;
    }

    public function isIsRecurr(): ?bool
    {
        return $this->isRecurr;
    }

    public function setIsRecurr(?bool $isRecurr): self
    {
        $this->isRecurr = $isRecurr;

        return $this;
    }

    public function getRecurrence(): ?int
    {
        return $this->recurrence;
    }

    public function setRecurrence(?int $recurrence): self
    {
        $this->recurrence = $recurrence;

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

    public function getMultiple(): ?int
    {
        return $this->multiple;
    }

    public function setMultiple(?int $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }
}
