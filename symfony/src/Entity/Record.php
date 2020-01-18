<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Tenant;
use App\Entity\Room;
use App\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordRepository")
 */
class Record
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Veuillez entrer une date d'entrée")
     * @Assert\Date()
     */
    private $entryDate;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez entrer le loyer")
     * @Assert\Type(type="float")
     */
    private $rent;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez entrer les charges")
     * @Assert\Type(type="float")
     */
    private $fixedCharge;

    /**
     * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank(message="Veuillez entrer la périodicité")
     */
    private $periodicity;

    /**
     * @ORM\Column(type="string", length=10)
	 * @Assert\NotBlank(message="Veuillez entrer l'index de révision")
     */
    private $revisionIndex;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Veuillez entrer la date de départ")
     * @Assert\Date()
     * @Assert\Expression("value >= this.getEntryDate()", message="Veuillez entrer une date supérieure à la date de début du contrat")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalInformation;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Tenant")
    * @ORM\JoinColumn(nullable=false)
    */
    private $tenant;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Room")
    * @ORM\JoinColumn(nullable=false)
    */
    private $room;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Veuillez entrer la date de signature")
     * @Assert\Date()
     */
    private $signingDate;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Person", cascade={"persist"})
    * @ORM\JoinColumn()
    */
    private $guarantor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): self
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    public function getRent(): ?float
    {
        return $this->rent;
    }

    public function setRent(float $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    public function getFixedCharge(): ?float
    {
        return $this->fixedCharge;
    }

    public function setFixedCharge(float $fixedCharge): self
    {
        $this->fixedCharge = $fixedCharge;

        return $this;
    }

    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    public function setPeriodicity(string $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getRevisionIndex(): ?string
    {
        return $this->revisionIndex;
    }

    public function setRevisionIndex(string $revisionIndex): self
    {
        $this->revisionIndex = $revisionIndex;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getSigningDate(): ?\DateTimeInterface
    {
        return $this->signingDate;
    }

    public function setSigningDate(\DateTimeInterface $signingDate): self
    {
        $this->signingDate = $signingDate;

        return $this;
    }

    public function getGuarantor(): ?Person
    {
        return $this->guarantor;
    }

    public function setGuarantor(?Person $guarantor): self
    {
        $this->guarantor = $guarantor;

        return $this;
    }
}
