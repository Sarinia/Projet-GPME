<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForgottenpwRepository")
 */
class Forgottenpw
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $requestdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expirationdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forgottenpws")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRequestdate(): ?\DateTimeInterface
    {
        return $this->requestdate;
    }

    public function setRequestdate(\DateTimeInterface $requestdate): self
    {
        $this->requestdate = $requestdate;

        return $this;
    }

    public function getExpirationdate(): ?\DateTimeInterface
    {
        return $this->expirationdate;
    }

    public function setExpirationdate(\DateTimeInterface $expirationdate): self
    {
        $this->expirationdate = $expirationdate;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

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
}
