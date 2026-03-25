<?php
// src/Entity/Event.php
namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: '`event`')]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    private ?int $seats = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    // Relation : un Event a plusieurs Reservations
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Reservation::class, cascade: ['persist', 'remove'])]
    private Collection $reservations;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'event')]
    private Collection $reservationsList;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->reservationsList = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getLocation(): ?string { return $this->location; }
    public function setLocation(string $location): static { $this->location = $location; return $this; }

    public function getSeats(): ?int { return $this->seats; }
    public function setSeats(int $seats): static { $this->seats = $seats; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getReservations(): Collection { return $this->reservations; }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setEvent($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getEvent() === $this) {
                $reservation->setEvent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservationsList(): Collection
    {
        return $this->reservationsList;
    }

    public function addReservationsList(Reservation $reservationsList): static
    {
        if (!$this->reservationsList->contains($reservationsList)) {
            $this->reservationsList->add($reservationsList);
            $reservationsList->setEvent($this);
        }

        return $this;
    }

    public function removeReservationsList(Reservation $reservationsList): static
    {
        if ($this->reservationsList->removeElement($reservationsList)) {
            // set the owning side to null (unless already changed)
            if ($reservationsList->getEvent() === $this) {
                $reservationsList->setEvent(null);
            }
        }

        return $this;
    }
}