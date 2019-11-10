<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Address;

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
     */
    private $name;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Address")
    * @ORM\JoinColumn(nullable=false)
    */
    private $address;

    /**
     * @ORM\Column(type="float")
     */
    private $surface;

    /**
     * @ORM\Column(type="blob")
     */
    private $inventory;

    /**
     * @ORM\Column(type="blob")
     */
    private $stateEntry;

    /**
     * @ORM\Column(type="blob")
     */
    private $stateExit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $furnish;

    /**
     * @ORM\Column(type="blob")
     */
    private $assurance;

    /**
     * @ORM\Column(type="blob")
     */
    private $lease;

    /**
     * @ORM\Column(type="blob")
     */
    private $diagnostics;

    /**
     * @ORM\Column(type="blob")
     */
    private $risks;

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

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setInventory($inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getStateEntry()
    {
        return $this->stateEntry;
    }

    public function setStateEntry($stateEntry): self
    {
        $this->stateEntry = $stateEntry;

        return $this;
    }

    public function getStateExit()
    {
        return $this->stateExit;
    }

    public function setStateExit($stateExit): self
    {
        $this->stateExit = $stateExit;

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

    public function getAssurance()
    {
        return $this->assurance;
    }

    public function setAssurance($assurance): self
    {
        $this->assurance = $assurance;

        return $this;
    }

    public function getLease()
    {
        return $this->lease;
    }

    public function setLease($lease): self
    {
        $this->lease = $lease;

        return $this;
    }

    public function getDiagnostics()
    {
        return $this->diagnostics;
    }

    public function setDiagnostics($diagnostics): self
    {
        $this->diagnostics = $diagnostics;

        return $this;
    }

    public function getRisks()
    {
        return $this->risks;
    }

    public function setRisks($risks): self
    {
        $this->risks = $risks;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }
}
