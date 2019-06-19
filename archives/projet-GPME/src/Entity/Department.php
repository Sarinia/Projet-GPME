<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
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
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exist;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Establishment", mappedBy="department")
     */
    private $establishment;

    public function __construct()
    {
        $this->establishment = new ArrayCollection();
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
     * @return Collection|Establishment[]
     */
    public function getEstablishment(): Collection
    {
        return $this->establishment;
    }

    public function addEstablishment(Establishment $establishment): self
    {
        if (!$this->establishment->contains($establishment)) {
            $this->establishment[] = $establishment;
            $establishment->setDepartment($this);
        }

        return $this;
    }

    public function removeEstablishment(Establishment $establishment): self
    {
        if ($this->establishment->contains($establishment)) {
            $this->establishment->removeElement($establishment);
            // set the owning side to null (unless already changed)
            if ($establishment->getDepartment() === $this) {
                $establishment->setDepartment(null);
            }
        }

        return $this;
    }
}
