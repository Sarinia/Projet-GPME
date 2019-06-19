<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
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
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $competency;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exist;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="activity")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cardsp", mappedBy="activity")
     */
    private $cardsps;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->cardsps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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

    public function getCompetency(): ?string
    {
        return $this->competency;
    }

    public function setCompetency(string $competency): self
    {
        $this->competency = $competency;

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
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setActivity($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getActivity() === $this) {
                $task->setActivity(null);
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
            $cardsp->setActivity($this);
        }

        return $this;
    }

    public function removeCardsp(Cardsp $cardsp): self
    {
        if ($this->cardsps->contains($cardsp)) {
            $this->cardsps->removeElement($cardsp);
            // set the owning side to null (unless already changed)
            if ($cardsp->getActivity() === $this) {
                $cardsp->setActivity(null);
            }
        }

        return $this;
    }
}
