<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $candidatenb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exist;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Status", mappedBy="User")
     */
    private $statuses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Forgottenpw", mappedBy="User")
     */
    private $forgottenpws;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="User")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cardsp", mappedBy="User")
     */
    private $cardsps;

    public function __construct()
    {
        $this->statuses = new ArrayCollection();
        $this->forgottenpws = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->cardsps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidatenb(): ?string
    {
        return $this->candidatenb;
    }

    public function setCandidatenb(?string $candidatenb): self
    {
        $this->candidatenb = $candidatenb;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getExist(): ?bool
    {
        return $this->exist;
    }

    public function setExist(bool $exist): self
    {
        $this->exist = $exist;

        return $this;
    }

    /**
     * @return Collection|Status[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(Status $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setUser($this);
        }

        return $this;
    }

    public function removeStatus(Status $status): self
    {
        if ($this->statuses->contains($status)) {
            $this->statuses->removeElement($status);
            // set the owning side to null (unless already changed)
            if ($status->getUser() === $this) {
                $status->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Forgottenpw[]
     */
    public function getForgottenpws(): Collection
    {
        return $this->forgottenpws;
    }

    public function addForgottenpw(Forgottenpw $forgottenpw): self
    {
        if (!$this->forgottenpws->contains($forgottenpw)) {
            $this->forgottenpws[] = $forgottenpw;
            $forgottenpw->setUser($this);
        }

        return $this;
    }

    public function removeForgottenpw(Forgottenpw $forgottenpw): self
    {
        if ($this->forgottenpws->contains($forgottenpw)) {
            $this->forgottenpws->removeElement($forgottenpw);
            // set the owning side to null (unless already changed)
            if ($forgottenpw->getUser() === $this) {
                $forgottenpw->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cardsp[]
     */
    public function getCardsps(): Collection
    {
        return $this->cardsps;
    }

    public function addCardsp(Cardsp $cardsp): self
    {
        if (!$this->cardsps->contains($cardsp)) {
            $this->cardsps[] = $cardsp;
            $cardsp->setUser($this);
        }

        return $this;
    }

    public function removeCardsp(Cardsp $cardsp): self
    {
        if ($this->cardsps->contains($cardsp)) {
            $this->cardsps->removeElement($cardsp);
            // set the owning side to null (unless already changed)
            if ($cardsp->getUser() === $this) {
                $cardsp->setUser(null);
            }
        }

        return $this;
    }
}
