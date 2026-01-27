<?php

namespace App\Entity;

use App\Repository\CoachingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachingRepository::class)]
class Coaching
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $datetime = null;

    #[ORM\Column(length: 50)]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $coaching_type = null;

    #[ORM\Column(length: 50)]
    #[ORM\JoinColumn(nullable: false)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'coachings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'coachings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coach $coach = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getCoachingType(): ?string
    {
        return $this->coaching_type;
    }

    public function setCoachingType(string $coaching_type): static
    {
        $this->coaching_type = $coaching_type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): static
    {
        $this->coach = $coach;

        return $this;
    }
}
