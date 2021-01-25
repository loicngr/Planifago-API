<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $userCapacity;

    /**
     * @ORM\Column(type="integer")
     */
    private $eventCapacity;

    /**
     * @ORM\ManyToMany(targetEntity=PlanOption::class, mappedBy="plan")
     */
    private $options;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="plan")
     */
    private $users;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getUserCapacity(): ?int
    {
        return $this->userCapacity;
    }

    public function setUserCapacity(int $userCapacity): self
    {
        $this->userCapacity = $userCapacity;

        return $this;
    }

    public function getEventCapacity(): ?int
    {
        return $this->eventCapacity;
    }

    public function setEventCapacity(int $eventCapacity): self
    {
        $this->eventCapacity = $eventCapacity;

        return $this;
    }

    /**
     * @return Collection|PlanOption[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(PlanOption $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addPlan($this);
        }

        return $this;
    }

    public function removeOption(PlanOption $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removePlan($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPlan($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPlan() === $this) {
                $user->setPlan(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
