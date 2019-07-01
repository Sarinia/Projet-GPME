<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numbersp;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Passport", inversedBy="card")
     * @ORM\JoinColumn(nullable=false)
     */
    private $passport;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Problem", inversedBy="card")
     */
    private $problem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modality", inversedBy="card")
     */
    private $modality;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Term", inversedBy="card")
     */
    private $term;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="card")
     */
    private $activity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $monthsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yearsp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $associate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getPassport(): ?Passport
    {
        return $this->passport;
    }

    public function setPassport(?Passport $passport): self
    {
        $this->passport = $passport;

        return $this;
    }

    public function getProblem(): ?Problem
    {
        return $this->problem;
    }

    public function setProblem(?Problem $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): self
    {
        $this->term = $term;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMonthsp(): ?string
    {
        return $this->monthsp;
    }

    public function setMonthsp(string $monthsp): self
    {
        $this->monthsp = $monthsp;

        return $this;
    }

    public function getYearsp(): ?string
    {
        return $this->yearsp;
    }

    public function setYearsp(string $yearsp): self
    {
        $this->yearsp = $yearsp;

        return $this;
    }

    public function getAssociate(): ?bool
    {
        return $this->associate;
    }

    public function setAssociate(bool $associate): self
    {
        $this->associate = $associate;

        return $this;
    }
}
