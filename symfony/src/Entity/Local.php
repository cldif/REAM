<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalRepository")
 */
class Local
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez entrer le nom du local")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="Veuillez entrer l'adresse du local")
     */
    private $address;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez entrer la surface")
     * @Assert\Type(type="float")
     */
    private $surface;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(type="bool")
     */
    private $furnish;
    
    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message="Veuillez entrer le type du local")
     */
    private $type;

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

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getFurnish(): ?bool
    {
        return $this->furnish;
    }

    public function setFurnish(bool $furnish): self
    {
        $this->furnish = $furnish;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
