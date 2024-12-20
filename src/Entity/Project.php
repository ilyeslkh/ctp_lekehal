<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'projects')]
    private ?Client $client = null;

    /**
     * @var Collection<int, Deliverable>
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Deliverable::class)]
    private Collection $deliverables;

    public function __construct()
    {
        $this->deliverables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Deliverable>
     */
    public function getDeliverables(): Collection
    {
        return $this->deliverables;
    }

    public function addDeliverable(Deliverable $deliverable): static
    {
        if (!$this->deliverables->contains($deliverable)) {
            $this->deliverables->add($deliverable);
            $deliverable->setProject($this);
        }

        return $this;
    }

    public function removeDeliverable(Deliverable $deliverable): static
    {
        if ($this->deliverables->removeElement($deliverable)) {
            // Set the owning side to null (unless already changed)
            if ($deliverable->getProject() === $this) {
                $deliverable->setProject(null);
            }
        }

        return $this;
    }
}

