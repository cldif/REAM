<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenantRepository")
 */
class Tenant extends Person
{
    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Person", cascade={"persist"})
    * @ORM\JoinColumn()
    */
    private $father;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Person", cascade={"persist"})
    * @ORM\JoinColumn()
    */
    private $mother;

    public function getFather(): ?Person
    {
        return $this->father;
    }

    public function setFather(?Person $father): self
    {
        $this->father = $father;

        return $this;
    }

    public function getMother(): ?Person
    {
        return $this->mother;
    }

    public function setMother(?Person $mother): self
    {
        $this->mother = $mother;

        return $this;
    }
}
