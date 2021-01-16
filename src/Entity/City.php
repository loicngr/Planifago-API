<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(mercure=true)
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
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
     * @ORM\Column(type="string", length=30)
     */
    private $postalcode;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="cities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="city")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Carpool::class, mappedBy="city")
     */
    private $carpools;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->carpools = new ArrayCollection();
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

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setCity($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCity() === $this) {
                $event->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Carpool[]
     */
    public function getCarpools(): Collection
    {
        return $this->carpools;
    }

    public function addCarpool(Carpool $carpool): self
    {
        if (!$this->carpools->contains($carpool)) {
            $this->carpools[] = $carpool;
            $carpool->setCity($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): self
    {
        if ($this->carpools->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getCity() === $this) {
                $carpool->setCity(null);
            }
        }

        return $this;
    }
}
