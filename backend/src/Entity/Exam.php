<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ExamRepository::class)]
class Exam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'exams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modality $modality = null;

    /**
     * @var Collection<int, ClinicalCase>
     */
    #[ORM\OneToMany(targetEntity: ClinicalCase::class, mappedBy: 'exam')]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): static
    {
        $this->modality = $modality;

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
            $clinicalCase->setExam($this);
        }

        return $this;
    }

    public function removeClinicalCase(ClinicalCase $clinicalCase): static
    {
        if ($this->clinicalCases->removeElement($clinicalCase)) {
            // set the owning side to null (unless already changed)
            if ($clinicalCase->getExam() === $this) {
                $clinicalCase->setExam(null);
            }
        }

        return $this;
    }
}
