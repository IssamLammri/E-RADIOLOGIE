<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ModalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ModalityRepository::class)]
class Modality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'modality')]
    private Collection $exams;

    public function __construct()
    {
        $this->exams = new ArrayCollection();
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

    /**
     * @return Collection<int, Exam>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): static
    {
        if (!$this->exams->contains($exam)) {
            $this->exams->add($exam);
            $exam->setModality($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getModality() === $this) {
                $exam->setModality(null);
            }
        }

        return $this;
    }
}
