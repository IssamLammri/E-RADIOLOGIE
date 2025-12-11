<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 50)]
    private ?string $gender = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $history = null;

    // Relation avec ClinicalCase (un patient peut avoir plusieurs cas cliniques)
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: ClinicalCase::class)]
    private $clinicalCases;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    public function getHistory(): ?string
    {
        return $this->history;
    }

    public function setHistory(?string $history): static
    {
        $this->history = $history;
        return $this;
    }

    public function getClinicalCases()
    {
        return $this->clinicalCases;
    }

    public function addClinicalCase(ClinicalCase $clinicalCase): static
    {
        if (!$this->clinicalCases->contains($clinicalCase)) {
            $this->clinicalCases[] = $clinicalCase;
            $clinicalCase->setPatient($this);
        }
        return $this;
    }

    public function removeClinicalCase(ClinicalCase $clinicalCase): static
    {
        if ($this->clinicalCases->removeElement($clinicalCase)) {
            // set the owning side to null (unless already changed)
            if ($clinicalCase->getPatient() === $this) {
                $clinicalCase->setPatient(null);
            }
        }
        return $this;
    }
}
