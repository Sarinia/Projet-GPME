<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exist;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sadmin", mappedBy="user", cascade={"persist", "remove"})
     */
    private $sadmin;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Admin", mappedBy="user", cascade={"persist", "remove"})
     */
    private $admin;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Teacher", mappedBy="user", cascade={"persist", "remove"})
     */
    private $teacher;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Student", mappedBy="user", cascade={"persist", "remove"})
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getExist(): ?bool
    {
        return $this->exist;
    }

    public function setExist(bool $exist): self
    {
        $this->exist = $exist;

        return $this;
    }

    public function getPassword() {
        return $this->hash;
    }

    public function getUsername() {
        return $this->email;
    }

    public function getSalt() {

    }

    public function getRoles() { 
        // foreach ($this->userRoles as $role) {
        //         $roles[]=$user->getTitle();
        //         return $roles; 
        // }
        $roles[]=$this->getTitle();
        return $roles; 
    }

    public function eraseCredentials() {

    }

    public function getSadmin(): ?Sadmin
    {
        return $this->sadmin;
    }

    public function setSadmin(?Sadmin $sadmin): self
    {
        $this->sadmin = $sadmin;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $sadmin === null ? null : $this;
        if ($newUser !== $sadmin->getUser()) {
            $sadmin->setUser($newUser);
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $admin === null ? null : $this;
        if ($newUser !== $admin->getUser()) {
            $admin->setUser($newUser);
        }

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $teacher === null ? null : $this;
        if ($newUser !== $teacher->getUser()) {
            $teacher->setUser($newUser);
        }

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $student === null ? null : $this;
        if ($newUser !== $student->getUser()) {
            $student->setUser($newUser);
        }

        return $this;
    }
}
