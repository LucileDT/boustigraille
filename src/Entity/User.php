<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * User is a class used to connect to the application. It contains the basic
 * information needed for this.
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(
 *      fields={"username"},
 *      message="Ce nom d'utilisateurice n'est pas disponible."
 * )
 */
class User implements UserInterface, \JsonSerializable, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string Field used to store the plain password in order
     * to populate the $password var with an encrypted value.
     */
    private $plainPassword;

    /**
     * @var Responsibility[]
     *
     * @ORM\ManyToMany(targetEntity="Responsibility")
     * @ORM\JoinTable(
     *      name="user_responsibility",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="responsibility_id", referencedColumnName="id")}
     * )
     */
    private $responsibilities;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $proteins;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $carbohydrates;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $energy;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, inversedBy="favedBy")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $favoriteRecipes;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $doShowUsernameOnRecipe;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $doShowWrittenMealListToOthers;

    /**
     * @ORM\OneToMany(targetEntity=NotificationReceipt::class, mappedBy="recipient", orphanRemoval=true)
     */
    private $notificationReceipts;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="sender")
     */
    private $sentNotifications;

    /**
     * @ORM\OneToMany(targetEntity=FollowMealList::class, mappedBy="follower", orphanRemoval=true)
     */
    private $followingMealLists;

    /**
     * @ORM\OneToMany(targetEntity=FollowMealList::class, mappedBy="followed", orphanRemoval=true)
     */
    private $followerMealLists;

    /**
     * @ORM\OneToMany(targetEntity=FollowUsernameOnRecipe::class, mappedBy="follower", orphanRemoval=true)
     */
    private $followingUsernamesOnRecipes;

    /**
     * @ORM\OneToMany(targetEntity=FollowUsernameOnRecipe::class, mappedBy="followed", orphanRemoval=true)
     */
    private $followerUsernamesOnRecipes;

    function __construct($id = -1, $username = NULL, $plainPassword = NULL, $responsibilities = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->plainPassword = $plainPassword;
        $this->responsibilities = $responsibilities;
        $this->favoriteRecipes = new ArrayCollection();
        $this->notificationReceipts = new ArrayCollection();
        $this->sentNotifications = new ArrayCollection();
        $this->followingMealLists = new ArrayCollection();
        $this->followerMealLists = new ArrayCollection();
        $this->followingUsernamesOnRecipes = new ArrayCollection();
        $this->followerUsernamesOnRecipes = new ArrayCollection();
        $this->doShowUsernameOnRecipe = false;
        $this->doShowWrittenMealListToOthers = false;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
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
    public function getRoles()
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

    /**
     * @return Collection<int, NotificationReceipt>
     */
    public function getNotificationReceipts(): Collection
    {
        return $this->notificationReceipts;
    }

    public function addNotificationReceipt(NotificationReceipt $notificationReceipt): self
    {
        if (!$this->notificationReceipts->contains($notificationReceipt)) {
            $this->notificationReceipts[] = $notificationReceipt;
            $notificationReceipt->setRecipient($this);
        }

        return $this;
    }

    public function removeNotificationReceipt(NotificationReceipt $notificationReceipt): self
    {
        if ($this->notificationReceipts->removeElement($notificationReceipt)) {
            // set the owning side to null (unless already changed)
            if ($notificationReceipt->getRecipient() === $this) {
                $notificationReceipt->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getSentNotifications(): Collection
    {
        return $this->sentNotifications;
    }

    public function addSentNotification(Notification $sentNotification): self
    {
        if (!$this->sentNotifications->contains($sentNotification)) {
            $this->sentNotifications[] = $sentNotification;
            $sentNotification->setSender($this);
        }

        return $this;
    }

    public function removeSentNotification(Notification $sentNotification): self
    {
        if ($this->sentNotifications->removeElement($sentNotification)) {
            // set the owning side to null (unless already changed)
            if ($sentNotification->getSender() === $this) {
                $sentNotification->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowMealList>
     */
    public function getFollowingMealLists(): Collection
    {
        return $this->followingMealLists;
    }

    public function addFollowingMealList(FollowMealList $followingMealList): self
    {
        if (!$this->followingMealLists->contains($followingMealList)) {
            $this->followingMealLists[] = $followingMealList;
            $followingMealList->setFollower($this);
        }

        return $this;
    }

    public function removeFollowingMealList(FollowMealList $followingMealList): self
    {
        if ($this->followingMealLists->removeElement($followingMealList)) {
            // set the owning side to null (unless already changed)
            if ($followingMealList->getFollower() === $this) {
                $followingMealList->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowMealList>
     */
    public function getFollowerMealLists(): Collection
    {
        return $this->followerMealLists;
    }

    public function addFollowerMealList(FollowMealList $followerMealList): self
    {
        if (!$this->followerMealLists->contains($followerMealList)) {
            $this->followerMealLists[] = $followerMealList;
            $followerMealList->setFollowed($this);
        }

        return $this;
    }

    public function removeFollowerMealList(FollowMealList $followerMealList): self
    {
        if ($this->followerMealLists->removeElement($followerMealList)) {
            // set the owning side to null (unless already changed)
            if ($followerMealList->getFollowed() === $this) {
                $followerMealList->setFollowed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowUsernameOnRecipe>
     */
    public function getFollowingUsernamesOnRecipes(): Collection
    {
        return $this->followingUsernamesOnRecipes;
    }

    public function addFollowingUsernameOnRecipe(FollowUsernameOnRecipe $followingUsernameOnRecipe): self
    {
        if (!$this->followingUsernamesOnRecipes->contains($followingUsernameOnRecipe)) {
            $this->followingUsernamesOnRecipes[] = $followingUsernameOnRecipe;
            $followingUsernameOnRecipe->setFollower($this);
        }

        return $this;
    }

    public function removeFollowingUsernameOnRecipe(FollowUsernameOnRecipe $followingUsernameOnRecipe): self
    {
        if ($this->followingUsernamesOnRecipes->removeElement($followingUsernameOnRecipe)) {
            // set the owning side to null (unless already changed)
            if ($followingUsernameOnRecipe->getFollower() === $this) {
                $followingUsernameOnRecipe->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowUsernameOnRecipe>
     */
    public function getFollowerUsernamesOnRecipes(): Collection
    {
        return $this->followerUsernamesOnRecipes;
    }

    public function addFollowerUsernameOnRecipe(FollowUsernameOnRecipe $followerUsernameOnRecipe): self
    {
        if (!$this->followerUsernamesOnRecipes->contains($followerUsernameOnRecipe)) {
            $this->followerUsernamesOnRecipes[] = $followerUsernameOnRecipe;
            $followerUsernameOnRecipe->setFollower($this);
        }

        return $this;
    }

    public function removeFollowerUsernameOnRecipe(FollowUsernameOnRecipe $followerUsernameOnRecipe): self
    {
        if ($this->followingUsernamesOnRecipes->removeElement($followerUsernameOnRecipe)) {
            // set the owning side to null (unless already changed)
            if ($followerUsernameOnRecipe->getFollower() === $this) {
                $followerUsernameOnRecipe->setFollower(null);
            }
        }

        return $this;
    }

    public function doFollowUsernameOnRecipe(User $user): bool
    {
        $followed = Criteria::create()->where(Criteria::expr()->eq('followed', $user));
        $followAccepted = Criteria::create()->where(Criteria::expr()->neq('acceptedAt', null));
        $proposedFollowings = $this->followingUsernamesOnRecipes->matching($followed);
        return !$proposedFollowings->matching($followAccepted)->isEmpty();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
        ];
    }
}
