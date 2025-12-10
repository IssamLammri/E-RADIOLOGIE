<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PathologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: PathologyRepository::class)]
class Pathology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $positiveDiagnosis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $etiologicalDiagnosis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evolutionComplications = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $differentialDiagnosis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conclusion = null;

    /**
     * @var Collection<int, ClinicalCase>
     */
    #[ORM\OneToMany(targetEntity: ClinicalCase::class, mappedBy: 'pathology')]
    private Collection $clinicalCases;

    public function __construct()
    {
        $this->clinicalCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): static
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getPositiveDiagnosis(): ?string
    {
        return $this->positiveDiagnosis;
    }

    public function setPositiveDiagnosis(?string $positiveDiagnosis): static
    {
        $this->positiveDiagnosis = $positiveDiagnosis;

        return $this;
    }

    public function getEtiologicalDiagnosis(): ?string
    {
        return $this->etiologicalDiagnosis;
    }

    public function setEtiologicalDiagnosis(?string $etiologicalDiagnosis): static
    {
        $this->etiologicalDiagnosis = $etiologicalDiagnosis;

        return $this;
    }

    public function getEvolutionComplications(): ?string
    {
        return $this->evolutionComplications;
    }

    public function setEvolutionComplications(?string $evolutionComplications): static
    {
        $this->evolutionComplications = $evolutionComplications;

        return $this;
    }

    public function getDifferentialDiagnosis(): ?string
    {
        return $this->differentialDiagnosis;
    }

    public function setDifferentialDiagnosis(?string $differentialDiagnosis): static
    {
        $this->differentialDiagnosis = $differentialDiagnosis;

        return $this;
    }

    public function getConclusion(): ?string
    {
        return $this->conclusion;
    }

    public function setConclusion(?string $conclusion): static
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    /**
     * @return Collection<int, ClinicalCase>
     */
    public function getClinicalCases(): Collection
    {
        return $this->clinicalCases;
    }

    public function addClinicalCase(ClinicalCase $clinicalCase): static
    {
        if (!$this->clinicalCases->contains($clinicalCase)) {
            $this->clinicalCases->add($clinicalCase);
            $clinicalCase->setPathology($this);
        }

        return $this;
    }

    public function removeClinicalCase(ClinicalCase $clinicalCase): static
    {
        if ($this->clinicalCases->removeElement($clinicalCase)) {
            // set the owning side to null (unless already changed)
            if ($clinicalCase->getPathology() === $this) {
                $clinicalCase->setPathology(null);
            }
        }

        return $this;
    }
}
