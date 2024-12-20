<?php
namespace App\Entity;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    #[ORM\Column(length: 255)]
    private ?string $email = null;
    #[ORM\Column(length: 255)]
    private ?string $phone = null;
    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Project::class)]
    private Collection $projects;
    /**
     * @var Collection<int, Testimonial>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Testimonial::class)]
    private Collection $testimonials;
    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->testimonials = new ArrayCollection();
    }
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
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }
    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }
    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setClient($this);
        }
        return $this;
    }
    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // Set the owning side to null (unless already changed)
            if ($project->getClient() === $this) {
                $project->setClient(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, Testimonial>
     */
    public function getTestimonials(): Collection
    {
        return $this->testimonials;
    }
    public function addTestimonial(Testimonial $testimonial): static
    {
        if (!$this->testimonials->contains($testimonial)) {
            $this->testimonials->add($testimonial);
            $testimonial->setClient($this);
        }
        return $this;
    }
    public function removeTestimonial(Testimonial $testimonial): static
    {
        if ($this->testimonials->removeElement($testimonial)) {
            // Set the owning side to null (unless already changed)
            if ($testimonial->getClient() === $this) {
                $testimonial->setClient(null);
            }
        }
        return $this;
    }
}