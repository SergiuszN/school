<?php

namespace App\Entity;

use App\Repository\EventRegistrationRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRegistrationRepository::class)]
class EventRegistration
{
    const STATUS_CREATED = 0;
    const STATUS_CONFIRMED = 10;
    const STATUS_PAYED = 100;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $accepted;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $phone;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
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

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getStatusString(): ?string
    {
        return match ($this->status) {
            self::STATUS_CREATED => 'Зареєструвався',
            self::STATUS_CONFIRMED => 'Підтверджено',
            self::STATUS_PAYED => 'Сплачено',
            default => null,
        };

    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): string
    {
        return md5($this->getId() . $this->getEmail());
    }

    public function isValidToken($token): bool
    {
        return $this->getToken() === $token;
    }
}
