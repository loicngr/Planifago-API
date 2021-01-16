<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
     * @ORM\Column(type="string", length=255)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scheduledAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scheduledEnd;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=ShoppingList::class, mappedBy="event", cascade={"persist", "remove"})
     */
    private $shoppingList;

    /**
     * @ORM\OneToMany(targetEntity=Carpool::class, mappedBy="event")
     */
    private $carpools;

    /**
     * @ORM\OneToOne(targetEntity=Playlist::class, mappedBy="event", cascade={"persist", "remove"})
     */
    private $playlist;

    /**
     * @ORM\OneToOne(targetEntity=Pool::class, mappedBy="event", cascade={"persist", "remove"})
     */
    private $pool;

    public function __construct()
    {
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

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeInterface $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getScheduledEnd(): ?\DateTimeInterface
    {
        return $this->scheduledEnd;
    }

    public function setScheduledEnd(\DateTimeInterface $scheduledEnd): self
    {
        $this->scheduledEnd = $scheduledEnd;

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

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(ShoppingList $shoppingList): self
    {
        // set the owning side of the relation if necessary
        if ($shoppingList->getEvent() !== $this) {
            $shoppingList->setEvent($this);
        }

        $this->shoppingList = $shoppingList;

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
            $carpool->setEvent($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): self
    {
        if ($this->carpools->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getEvent() === $this) {
                $carpool->setEvent(null);
            }
        }

        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(Playlist $playlist): self
    {
        // set the owning side of the relation if necessary
        if ($playlist->getEvent() !== $this) {
            $playlist->setEvent($this);
        }

        $this->playlist = $playlist;

        return $this;
    }

    public function getPool(): ?Pool
    {
        return $this->pool;
    }

    public function setPool(Pool $pool): self
    {
        // set the owning side of the relation if necessary
        if ($pool->getEvent() !== $this) {
            $pool->setEvent($this);
        }

        $this->pool = $pool;

        return $this;
    }
}
