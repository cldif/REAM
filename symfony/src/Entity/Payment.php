<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Record;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $supposedDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $paidDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Record")
    * @ORM\JoinColumn(nullable=false)
    */
    private $record;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupposedDate(): ?\DateTimeInterface
    {
        return $this->supposedDate;
    }

    public function setSupposedDate(\DateTimeInterface $supposedDate): self
    {
        $this->supposedDate = $supposedDate;

        return $this;
    }

    public function getPaidDate(): ?\DateTimeInterface
    {
        return $this->paidDate;
    }

    public function setPaidDate(\DateTimeInterface $paidDate): self
    {
        $this->paidDate = $paidDate;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRecord(): ?Record
    {
        return $this->record;
    }

    public function setRecord(?Record $record): self
    {
        $this->record = $record;

        return $this;
    }
}
