<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"person" = "Person", "tenant" = "Tenant"})
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez entrer un nom")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez entrer un prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Length(max=5)
     * @Assert\NotBlank(message="Veuillez entrer un genre")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message="Veuillez entrer un numéro de téléphone fixe")
     * @Assert\Regex("/^0[0-9]{9}$/", message="Le format du numéro de téléphone est incorrect (ex : 0612345678)")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message="Veuillez entrer un numéro de téléphone portable")
     * @Assert\Regex("/^0[0-9]{9}$/", message="Le format du numéro de téléphone est incorrect (ex : 0612345678)")
     */
    private $mobilePhone;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez entrer un email")
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="Veuillez entrer une adresse postale")
     */
    private $address;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Veuillez entrer une date de naissance")
     * @Assert\Date()
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez entrer un lieu de naissance")
     */
    private $birthPlace;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $profession;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

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

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(string $birthPlace): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

}
