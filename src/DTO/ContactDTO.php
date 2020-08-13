<?php

namespace App\DTO;

class ContactDTO
{
    /** @var string|null */
    private $name;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $question;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string|null $question
     */
    public function setQuestion(?string $question): void
    {
        $this->question = $question;
    }
}