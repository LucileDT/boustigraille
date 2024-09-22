<?php
namespace App\Entity;

use App\Notifier\BoustigrailleRecipientInterface;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * User is a class used to connect to the application. It contains the basic
 * information needed for this.
 *
 *
 */
#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: \App\Repository\UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: "Ce nom d'utilisateurice n'est pas disponible.")]
class User implements UserInterface, \JsonSerializable, PasswordAuthenticatedUserInterface, BoustigrailleRecipientInterface
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    /**
     * @var string Field used to store the plain password in order
     * to populate the $password var with an encrypted value.
     */
    private $plainPassword;

    /**
     * @var Responsibility[]
     */
    #[ORM\JoinTable(name: 'user_responsibility')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'responsibility_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Responsibility::class)]
    private $responsibilities;

    #[ORM\Column(type: 'float', nullable: true)]
    private $proteins;

    #[ORM\Column(type: 'float', nullable: true)]
    private $carbohydrates;

    #[ORM\Column(type: 'float', nullable: true)]
    private $fat;

    #[ORM\Column(type: 'float', nullable: true)]
    private $energy;

    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'favedBy')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private $favoriteRecipes;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $doShowUsernameOnRecipe;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $doShowWrittenMealListToOthers;

    #[ORM\OneToMany(mappedBy: 'followed', targetEntity: FollowProposition::class)]
    private Collection $followPropositionsSent;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: FollowProposition::class)]
    private Collection $followPropositionsReceived;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: NotificationSent::class)]
    private Collection $notificationsSent;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: NotificationReceived::class)]
    private Collection $notificationsReceived;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    function __construct($id = -1, $username = NULL, $plainPassword = NULL, $responsibilities = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->plainPassword = $plainPassword;
        $this->responsibilities = $responsibilities;
        $this->favoriteRecipes = new ArrayCollection();
        $this->doShowUsernameOnRecipe = false;
        $this->doShowWrittenMealListToOthers = false;
        $this->followPropositionsSent = new ArrayCollection();
        $this->followPropositionsReceived = new ArrayCollection();
        $this->notificationsSent = new ArrayCollection();
        $this->notificationsReceived = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username The username of the user.
     *
     * @return User
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set plainPassword
     *
     * @param string $plainPassword The password in plain text.
     *
     * @return User
     */
    function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password The encoded password.
     *
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get user's responsibilities
     *
     * @return Responsibility[]
     */
    function getResponsibilities()
    {
        return $this->responsibilities;
    }

    /**
     * Set user's responsibilities
     *
     * @param Responsibility[] $responsibilities The responsibilities to set.
     *
     * @return User
     */
    function setResponsibilities(array $responsibilities)
    {
        $this->responsibilities = $responsibilities;
        return $this;
    }

    /**
     * Add a responsibility to the user
     *
     * @param Responsibility $responsibility The responsibility to add.
     */
    public function addResponsibility(Responsibility $responsibility)
    {
        if (!$this->hasResponsibility($responsibility))
        {
            $this->responsibilities[] = $responsibility;
        }
    }

    /**
     * Remove a responsibility from the user
     *
     * @param Responsibility $responsibilityToRemove The responsibility to remove.
     */
    function removeResponsibility(Responsibility $responsibilityToRemove)
    {
        $ownedResponsibilities = $this->getResponsibilities();
        foreach ($ownedResponsibilities as $index => $ownedResponsibility)
        {
            if ($ownedResponsibility->getId() === $responsibilityToRemove->getId())
            {
                unset($ownedResponsibilities[$index]);
            }
        }
    }

    /**
     * Check if user has this responsibility
     *
     * @param Responsibility $responsibility The responsibility to add.
     *
     * @return bool
     */
    function hasResponsibility(Responsibility $responsibility)
    {
        foreach ($this->getResponsibilities() as $ownedResponsibility)
        {
            if ($ownedResponsibility->getId() === $responsibility->getId())
            {
                return true;
            }
        }
        return false;
    }

    public function eraseCredentials()
    {

    }

    /**
     * Return an array of strings containing the role list
     * (each string being a responsibility label used by Symfony to define a user's role)
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->responsibilities as $responsibility)
        {
            $roles[] = $responsibility->getCode();
        }
        return $roles;
    }

    public function getSalt()
    {

    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    public function setProteins(?float $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getEnergy(): ?float
    {
        return $this->energy;
    }

    public function setEnergy(?float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getFavoriteRecipes(): Collection
    {
        return $this->favoriteRecipes;
    }

    public function addFavoriteRecipe(Recipe $favoriteRecipe): self
    {
        if (!$this->favoriteRecipes->contains($favoriteRecipe)) {
            $this->favoriteRecipes[] = $favoriteRecipe;
        }

        return $this;
    }

    public function removeFavoriteRecipe(Recipe $favoriteRecipe): self
    {
        $this->favoriteRecipes->removeElement($favoriteRecipe);

        return $this;
    }

    /**
     * Check if the user has marked a specific recipe as favorite
     */
    public function hasFaved(Recipe $recipe): bool
    {
        return $this->favoriteRecipes->contains($recipe);
    }

    public function doShowUsernameOnRecipe(): bool
    {
        return $this->doShowUsernameOnRecipe;
    }

    public function setDoShowUsernameOnRecipe(bool $doShowUsernameOnRecipe): self
    {
        $this->doShowUsernameOnRecipe = $doShowUsernameOnRecipe;

        return $this;
    }

    public function doShowWrittenMealListToOthers(): bool
    {
        return $this->doShowWrittenMealListToOthers;
    }

    public function setdoShowWrittenMealListToOthers(bool $doShowWrittenMealListToOthers): self
    {
        $this->doShowWrittenMealListToOthers = $doShowWrittenMealListToOthers;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
        ];
    }

    /**
     * @return Collection<int, FollowProposition>
     */
    public function getFollowPropositionsSent(): Collection
    {
        return $this->followPropositionsSent;
    }

    public function addFollowPropositionsSent(FollowProposition $followPropositionsSent): static
    {
        if (!$this->followPropositionsSent->contains($followPropositionsSent)) {
            $this->followPropositionsSent->add($followPropositionsSent);
            $followPropositionsSent->setFollower($this);
        }

        return $this;
    }

    public function removeFollowPropositionsSent(FollowProposition $followPropositionsSent): static
    {
        if ($this->followPropositionsSent->removeElement($followPropositionsSent)) {
            // set the owning side to null (unless already changed)
            if ($followPropositionsSent->getFollower() === $this) {
                $followPropositionsSent->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowProposition>
     */
    public function getFollowPropositionsReceived(): Collection
    {
        return $this->followPropositionsReceived;
    }

    public function addFollowPropositionsReceived(FollowProposition $followPropositionsReceived): static
    {
        if (!$this->followPropositionsReceived->contains($followPropositionsReceived)) {
            $this->followPropositionsReceived->add($followPropositionsReceived);
            $followPropositionsReceived->setFollowed($this);
        }

        return $this;
    }

    public function removeFollowPropositionsReceived(FollowProposition $followPropositionsReceived): static
    {
        if ($this->followPropositionsReceived->removeElement($followPropositionsReceived)) {
            // set the owning side to null (unless already changed)
            if ($followPropositionsReceived->getFollowed() === $this) {
                $followPropositionsReceived->setFollowed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationSent>
     */
    public function getNotificationsSent(): Collection
    {
        return $this->notificationsSent;
    }

    public function addNotificationSent(NotificationSent $notificationSent): static
    {
        if (!$this->notificationsSent->contains($notificationSent)) {
            $this->notificationsSent->add($notificationSent);
            $notificationSent->setSender($this);
        }

        return $this;
    }

    public function removeNotificationSent(NotificationSent $notificationSent): static
    {
        if ($this->notificationsSent->removeElement($notificationSent)) {
            // set the owning side to null (unless already changed)
            if ($notificationSent->getSender() === $this) {
                $notificationSent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationReceived>
     */
    public function getNotificationsReceived(): Collection
    {
        return $this->notificationsReceived;
    }

    public function addNotificationReceived(NotificationReceived $notificationReceived): static
    {
        if (!$this->notificationsReceived->contains($notificationReceived)) {
            $this->notificationsReceived->add($notificationReceived);
            $notificationReceived->setRecipient($this);
        }

        return $this;
    }

    public function removeNotificationReceived(NotificationReceived $notificationReceived): static
    {
        if ($this->notificationsReceived->removeElement($notificationReceived)) {
            // set the owning side to null (unless already changed)
            if ($notificationReceived->getRecipient() === $this) {
                $notificationReceived->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }
}
