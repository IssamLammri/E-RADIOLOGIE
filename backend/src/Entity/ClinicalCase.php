<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClinicalCaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ClinicalCaseRepository::class)]
class ClinicalCase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $patientAge = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $symptoms = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $images = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $imageComment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conclusion = null;

    #[ORM\ManyToOne(inversedBy: 'clinicalCases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $exam = null;

    #[ORM\ManyToOne(inversedBy: 'clinicalCases')]
    private ?Pathology $pathology = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientAge(): ?int
    {
        return $this->patientAge;
    }

    public function setPatientAge(int $patientAge): static
    {
        $this->patientAge = $patientAge;

        return $this;
    }

    public function getSymptoms(): ?string
    {
        return $this->symptoms;
    }

    public function setSymptoms(?string $symptoms): static
    {
        $this->symptoms = $symptoms;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getImageComment(): ?string
    {
        return $this->imageComment;
    }

    public function setImageComment(?string $imageComment): static
    {
        $this->imageComment = $imageComment;

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

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }

    public function getPathology(): ?Pathology
    {
        return $this->pathology;
    }

    public function setPathology(?Pathology $pathology): static
    {
        $this->pathology = $pathology;

        return $this;
    }
}
