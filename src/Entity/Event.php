<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    const AVAILABLE_FOR_REGISTRATION = '+6 month';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $date;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $preview;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    /**
     * @var ArrayCollection|EventRegistration[]
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventRegistration::class)]
    private Collection|array $registrations;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $invoice;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $program;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
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

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return ArrayCollection|EventRegistration[]
     */
    public function getRegistrations(): Collection|array
    {
        return $this->registrations;
    }

    public function addRegistration(EventRegistration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setEvent($this);
        }

        return $this;
    }

    public function removeRegistration(EventRegistration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getEvent() === $this) {
                $registration->setEvent(null);
            }
        }

        return $this;
    }

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getProgram(): ?string
    {
        return $this->program;
    }

    public function setProgram(string $program): self
    {
        $this->program = $program;

        return $this;
    }
}
