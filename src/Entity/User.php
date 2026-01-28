<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public const ROLE_JOUEUR   = 'ROLE_JOUEUR';
    public const ROLE_ABONNE   = 'ROLE_ABONNE';
    public const ROLE_COACH    = 'ROLE_COACH';
    public const ROLE_ADMIN    = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $registration_date = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Coach $coach = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist'])]
    private ?Subscription $subscription = null;

    /**
     * @var Collection<int, Coaching>
     */
    #[ORM\OneToMany(targetEntity: Coaching::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $coachings;

    /**
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $feedback;

    public function __construct()
    {
        $this->coachings = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = self::ROLE_JOUEUR;
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getRegistrationDate(): ?\DateTime
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTime $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(Coach $coach): static
    {
        // set the owning side of the relation if necessary
        if ($coach->getUser() !== $this) {
            $coach->setUser($this);
        }

        $this->coach = $coach;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): static
    {
        // set the owning side of the relation if necessary
        if ($subscription->getUser() !== $this) {
            $subscription->setUser($this);
        }

        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Collection<int, Coaching>
     */
    public function getCoachings(): Collection
    {
        return $this->coachings;
    }

    public function addCoaching(Coaching $coaching): static
    {
        if (!$this->coachings->contains($coaching)) {
            $this->coachings->add($coaching);
            $coaching->setUser($this);
        }

        return $this;
    }

    public function removeCoaching(Coaching $coaching): static
    {
        if ($this->coachings->removeElement($coaching)) {
            // set the owning side to null (unless already changed)
            if ($coaching->getUser() === $this) {
                $coaching->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setUser($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getUser() === $this) {
                $feedback->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
