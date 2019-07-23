<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminRepository")
 */
class Admin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="admin", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Establishment", inversedBy="admins")
     */
    private $establishment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Postit", mappedBy="admin")
     */
    private $postits;

    public function __construct()
    {
        $this->postits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Postit[]
     */
    public function getPostits(): Collection
    {
        return $this->postits;
    }

    public function addPostit(Postit $postit): self
    {
        if (!$this->postits->contains($postit)) {
            $this->postits[] = $postit;
            $postit->setAdmin($this);
        }

        return $this;
    }

    public function removePostit(Postit $postit): self
    {
        if ($this->postits->contains($postit)) {
            $this->postits->removeElement($postit);
            // set the owning side to null (unless already changed)
            if ($postit->getAdmin() === $this) {
                $postit->setAdmin(null);
            }
        }

        return $this;
    }
}
