<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeacherRepository")
 */
class Teacher
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="teacher", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Establishment", inversedBy="teachers")
     */
    private $establishment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classroom", inversedBy="teachers")
     */
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Postit", mappedBy="teacher")
     */
    private $postits;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
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
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): self
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms[] = $classroom;
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): self
    {
        if ($this->classrooms->contains($classroom)) {
            $this->classrooms->removeElement($classroom);
        }

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
            $postit->setTeacher($this);
        }

        return $this;
    }

    public function removePostit(Postit $postit): self
    {
        if ($this->postits->contains($postit)) {
            $this->postits->removeElement($postit);
            // set the owning side to null (unless already changed)
            if ($postit->getTeacher() === $this) {
                $postit->setTeacher(null);
            }
        }

        return $this;
    }
}
