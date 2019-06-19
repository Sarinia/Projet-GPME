<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student
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
    private $candidateNb;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Establishment", inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $establishment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classroom", inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classroom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="student")
     */
    private $cards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Passport", mappedBy="student")
     */
    private $passports;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->passports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidateNb(): ?string
    {
        return $this->candidateNb;
    }

    public function setCandidateNb(string $candidateNb): self
    {
        $this->candidateNb = $candidateNb;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

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

    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

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

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setStudent($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getStudent() === $this) {
                $card->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Passport[]
     */
    public function getPassports(): Collection
    {
        return $this->passports;
    }

    public function addPassport(Passport $passport): self
    {
        if (!$this->passports->contains($passport)) {
            $this->passports[] = $passport;
            $passport->setStudent($this);
        }

        return $this;
    }

    public function removePassport(Passport $passport): self
    {
        if ($this->passports->contains($passport)) {
            $this->passports->removeElement($passport);
            // set the owning side to null (unless already changed)
            if ($passport->getStudent() === $this) {
                $passport->setStudent(null);
            }
        }

        return $this;
    }
}
