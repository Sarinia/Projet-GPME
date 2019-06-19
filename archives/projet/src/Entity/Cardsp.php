<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardspRepository")
 */
class Cardsp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $prob1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $prob2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $prob3;

    /**
     * @ORM\Column(type="integer")
     */
    private $numbersp;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mod1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mod2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mod3;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cond1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cond2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cond3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entitledsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infossp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $framesp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $problemmanagsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $problemcomosp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $problemcomwsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $actorssp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $targetsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conditionssp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resourcessp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerssp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productionssp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $writtensp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oralsp;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contributionsp;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $analysissp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cardsps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="cardsps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProb1(): ?bool
    {
        return $this->prob1;
    }

    public function setProb1(?bool $prob1): self
    {
        $this->prob1 = $prob1;

        return $this;
    }

    public function getProb2(): ?bool
    {
        return $this->prob2;
    }

    public function setProb2(?bool $prob2): self
    {
        $this->prob2 = $prob2;

        return $this;
    }

    public function getProb3(): ?bool
    {
        return $this->prob3;
    }

    public function setProb3(?bool $prob3): self
    {
        $this->prob3 = $prob3;

        return $this;
    }

    public function getNumbersp(): ?int
    {
        return $this->numbersp;
    }

    public function setNumbersp(int $numbersp): self
    {
        $this->numbersp = $numbersp;

        return $this;
    }

    public function getMod1(): ?bool
    {
        return $this->mod1;
    }

    public function setMod1(?bool $mod1): self
    {
        $this->mod1 = $mod1;

        return $this;
    }

    public function getMod2(): ?bool
    {
        return $this->mod2;
    }

    public function setMod2(?bool $mod2): self
    {
        $this->mod2 = $mod2;

        return $this;
    }

    public function getMod3(): ?bool
    {
        return $this->mod3;
    }

    public function setMod3(?bool $mod3): self
    {
        $this->mod3 = $mod3;

        return $this;
    }

    public function getCond1(): ?bool
    {
        return $this->cond1;
    }

    public function setCond1(?bool $cond1): self
    {
        $this->cond1 = $cond1;

        return $this;
    }

    public function getCond2(): ?bool
    {
        return $this->cond2;
    }

    public function setCond2(?bool $cond2): self
    {
        $this->cond2 = $cond2;

        return $this;
    }

    public function getCond3(): ?bool
    {
        return $this->cond3;
    }

    public function setCond3(?bool $cond3): self
    {
        $this->cond3 = $cond3;

        return $this;
    }

    public function getEntitledsp(): ?string
    {
        return $this->entitledsp;
    }

    public function setEntitledsp(?string $entitledsp): self
    {
        $this->entitledsp = $entitledsp;

        return $this;
    }

    public function getInfossp(): ?string
    {
        return $this->infossp;
    }

    public function setInfossp(?string $infossp): self
    {
        $this->infossp = $infossp;

        return $this;
    }

    public function getFramesp(): ?string
    {
        return $this->framesp;
    }

    public function setFramesp(?string $framesp): self
    {
        $this->framesp = $framesp;

        return $this;
    }

    public function getProblemmanagsp(): ?string
    {
        return $this->problemmanagsp;
    }

    public function setProblemmanagsp(?string $problemmanagsp): self
    {
        $this->problemmanagsp = $problemmanagsp;

        return $this;
    }

    public function getProblemcomosp(): ?string
    {
        return $this->problemcomosp;
    }

    public function setProblemcomosp(?string $problemcomosp): self
    {
        $this->problemcomosp = $problemcomosp;

        return $this;
    }

    public function getProblemcomwsp(): ?string
    {
        return $this->problemcomwsp;
    }

    public function setProblemcomwsp(?string $problemcomwsp): self
    {
        $this->problemcomwsp = $problemcomwsp;

        return $this;
    }

    public function getActorssp(): ?string
    {
        return $this->actorssp;
    }

    public function setActorssp(?string $actorssp): self
    {
        $this->actorssp = $actorssp;

        return $this;
    }

    public function getTargetsp(): ?string
    {
        return $this->targetsp;
    }

    public function setTargetsp(?string $targetsp): self
    {
        $this->targetsp = $targetsp;

        return $this;
    }

    public function getConditionssp(): ?string
    {
        return $this->conditionssp;
    }

    public function setConditionssp(?string $conditionssp): self
    {
        $this->conditionssp = $conditionssp;

        return $this;
    }

    public function getResourcessp(): ?string
    {
        return $this->resourcessp;
    }

    public function setResourcessp(?string $resourcessp): self
    {
        $this->resourcessp = $resourcessp;

        return $this;
    }

    public function getAnswerssp(): ?string
    {
        return $this->answerssp;
    }

    public function setAnswerssp(?string $answerssp): self
    {
        $this->answerssp = $answerssp;

        return $this;
    }

    public function getProductionssp(): ?string
    {
        return $this->productionssp;
    }

    public function setProductionssp(?string $productionssp): self
    {
        $this->productionssp = $productionssp;

        return $this;
    }

    public function getWrittensp(): ?string
    {
        return $this->writtensp;
    }

    public function setWrittensp(?string $writtensp): self
    {
        $this->writtensp = $writtensp;

        return $this;
    }

    public function getOralsp(): ?string
    {
        return $this->oralsp;
    }

    public function setOralsp(?string $oralsp): self
    {
        $this->oralsp = $oralsp;

        return $this;
    }

    public function getContributionsp(): ?string
    {
        return $this->contributionsp;
    }

    public function setContributionsp(?string $contributionsp): self
    {
        $this->contributionsp = $contributionsp;

        return $this;
    }

    public function getAnalysissp(): ?string
    {
        return $this->analysissp;
    }

    public function setAnalysissp(?string $analysissp): self
    {
        $this->analysissp = $analysissp;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }
}
